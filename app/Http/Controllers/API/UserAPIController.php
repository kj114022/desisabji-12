<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:54

 *
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\UploadRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;

class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;
    private $smsController;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository, UploadRepositoryInterface $uploadRepository, RoleRepositoryInterface $roleRepository, CustomFieldRepositoryInterface $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    function loginwithphone(Request $request)
    {

        try {
            $this->validate($request, [
                'phone' => 'required',
                'password' => 'required',
            ]);
            if (auth()->attempt(['mobile' => $request->input('phone'), 'password' => $request->input('password'), 'status' => '1'])) {
                 
                  Log::debug('login mobile :: '.$request->input('phone'));
                // Authentication passed...
                $user = auth()->user();
                Log::debug('user autenticated :: '.$user);
                $user->device_token = $request->input('device_token', '');
                $user->save();
                $response = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'login_id' => $user->login_id,
                    'customer_id' => $user->customer_id,    
                    'api_token' => $user->api_token,
                    'device_token' => $user->device_token,
                   // 'avatar' => $user->getAvatarUrl(),
                    'status' => $user->status,
                    'city_id' => $user->city_id,
                ];
                return $this->sendResponse($response, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }
    
     function login(Request $request)
    {

        try {
            $this->validate($request, [
                'login_id' => 'required',
                'password' => 'required',
            ]);
            $user = $this->userRepository->findByField('login_id', $request->input('login_id'))->first();
            Log::debug('Api login user :: ' . json_encode($user));
            if($user != null){
              if($user->mobile_verify == 1){                 
                    if (auth()->attempt(['login_id' => $request->input('login_id'), 'password' => $request->input('password'), 'status' => '1'])) {
                        // Authentication passed...
                        $user = auth()->user();
                       //send sms to user
                        $otp = $this->userRepository->generateNumericOTP(6, $user->mobile, $user);
                        $user->mobile_otp = $otp;
                        $otp_expires_at = now()->addMinutes(2);
                        $user->otp_expires_at = $otp_expires_at;
                       if($user->email != null){
                            $this->sendEmailForOtp($otp, $user);
                            Log::info('Email sent to user: ' . $user->email);
                        }
                        $user->device_token = $request->input('device_token', '');
                        $user->save();
                           $response = [
                                'user_id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'mobile' => $user->mobile,
                                'login_id' => $user->login_id,
                                'customer_id' => $user->customer_id,    
                                'api_token' => $user->api_token,
                                'device_token' => $user->device_token,
                               // 'avatar' => $user->getAvatarUrl(),
                                'otp_expires_at' => $otp_expires_at,
                                'mobile_verify' => (bool)$user->mobile_verify,
                                'mobile_otp' => $otp,
                                'status' => $user->status,
                                'city_id' => $user->city_id,
                            ];
                        return $this->sendResponse($response, 'User retrieved successfully');
                    } else{
                        return $this->sendError('Authenication is failed',401);
                    }               
                }
                else{
                    return $this->sendError('Please verify your mobile first',401);
                } 
            }else{
                return $this->sendError('User does not  exist',401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
       // die('123');
      
        try {
            $this->validate($request, [
                'name' => 'required',
            //    'email' => 'unique:users|email',
                'mobile' => 'required|unique:users',
               // 'phone' => 'required',
                'password' => 'required',
            ]);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            $user->login_id = $request->input('mobile');
            $customerId = "C".$request->input('mobile');
            $user->customer_id = $customerId;
            if($user->email != null){
                $user->email = $request->input('email');
            }else{
                $user->email = "info@desisabji.com";
            }
            
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = str_random(60);
            $user->save();
            $smsController = new SMSAPIController($this->userRepository);
            $otp = $smsController->generateNumericOTP(6,$user->mobile,$user);
            $smsController->sendUserOTPSms($otp,$user->mobile);
            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }


            if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                $user->addMedia(public_path('images/avatar_default_temp.png'))
                    ->withCustomProperties(['uuid' => bcrypt(str_random())])
                    ->toMediaCollection('avatar');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }


        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');

    }

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'app_short_description' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'fcm_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
                'home_section_1'=> '',
                'home_section_2'=> '',
                'home_section_3'=> '',
                'home_section_4'=> '',
                'home_section_5'=> '',
                'home_section_6'=> '',
                'home_section_7'=> '',
                'home_section_8'=> '',
                'home_section_9'=> '',
                'home_section_10'=> '',
                'home_section_11'=> '',
                'home_section_12'=> '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        Log::debug('Api update user function :: ' . json_encode($request->all()));
        $this->validate($request, [ 
            'name' => 'required',
            'email' => 'email|unique:users,email,' . $id,
            'mobile' => 'required|unique:users,mobile,' . $id,
         //   'device_token' => 'nullable',
            'first_name' => 'nullable|min:1',
            'last_name' => 'nullable|min:1',
            'city_id' => 'nullable|exists:cities,id',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
        ]);
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError('Reset link not sent', 401);
        }

    }
    
    function resetPasswordByPhone(Request $request)
     {
            Log::debug('Api reset password by phone function :: ' . json_encode($request->all()));
            $this->validate($request, ['phone' => 'required|regex:/^[0-9]{10}$/|min:10']);
            $user = $this->userRepository->findByField('mobile', $request->input('phone'))->first();
            Log::debug('Api reset password by phone user :: ' . json_encode($user));
             if (!$user) {
               return $this->sendError('Phone number is not registered with us.', 401);
            }
            $response = [
                'user_id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'message' => 'User found successfully. Please check your mobile for OTP.',
            ];
             return $response;
     }

    
    /**
         * Reset password.
         * @param Request $request
         *
         */
        public function changePassword(Request $request)
        {
            $user = $this->userRepository->findByField('mobile', $request->input('phone'))->first();
            if (!$user) {
                  return $this->sendError('User not found', 401);
            }
            
            try {
                if ($request->has('password')) {
                     $user->password = Hash::make($request->input('password'));
                     $user->save();
                }
            } catch (ValidatorException $e) {
                return $this->sendError($e->getMessage(), 401);
            }

            $response = [
                'user_id' => $user->id,
                'name' => $user->name,
                'message' => 'Password updated successfully',
            ];
            return $this->sendResponse($response, __('lang.updated_successfully', ['operator' => __('lang.user')]));
        }

        public function sendEmailForOtp($otp,$user)
    {
        $data = [
            'name' => $user->name,
            'message' => 'Your OTP for registration is ' . $otp
        ];

        Log::info('Preparing to send email with data: ' . json_encode($data));
        $this->userRepository->sendEmail($data);
        return response()->json(['success' => 'Email sent successfully.']);
    }
}
