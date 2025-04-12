<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:54
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Referral;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\ReferralRepository;
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
    private $referralRepository;
    private $smsController;          
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo,ReferralRepository $referralRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
        $this->referralRepository = $referralRepo;
        $this->smsController = new SMSAPIController();          
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
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (Exception $e) {
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
            if($user != null){
              if($user->mobile_verify == 1){                 
                    if (auth()->attempt(['login_id' => $request->input('login_id'), 'password' => $request->input('password'), 'status' => '1'])) {
                        // Authentication passed...
                        $user = auth()->user();
                        $user->device_token = $request->input('device_token', '');
                        $user->save();
                        return $this->sendResponse($user, 'User retrieved successfully');
                    } else{
                        return $this->sendError('Authenication is failed',401);
                    }               
                }
                else{
                    return $this->sendError('Please verify your mobile first',401);
                } 
            }else{
                return $this->sendError('User does not  exist',402);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 403);
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
       Log::debug("UserAPIController.register :: started");
      
        try {
            $this->validate($request, [
                'name' => 'required',
            //    'email' => 'unique:users|email',
                'mobile' => 'required|unique:users',
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
            if($request->input('reference_no') != null){
                $user->reference_no =$request->input('reference_no');
            }
            $user->save();
            try {
               
              $this->smsController->sendUserOTPSms($user);

            } catch (Exception $e) {
                Log::error("UserAPIController:sendUserOTPSms Sms failure:".$e->getMessage());
            }
            
            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);
            if($request->input('reference_no') != null ||  $request->input('reference_no') != ""){
                $referral = new Referral;
                $referral->customer_no = $request->input('mobile');
                $referral->referral_no = $request->input('reference_no');
                $referral->referral_type = "M";
                $referral->referral_amt = "150";
                $referral->payment_status = "I";
                $referral->save();
                $wallet = new Wallet;
                $wallet->customer_id = $customerId;
                $wallet->wallet_amt = "150";
                $wallet->save();
               /* try {
                  $this->smsController->referralSms($request->input('reference_no'),"Rs 25","Rs 25","Next 3");

                } catch (Exception $e) {
                    Log::error("UserAPIController:referralSms Sms failure:".$e->getMessage());
                }*/
            }
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
          /*  try {
                    $this->registrationPromoSms($user->mobile);
                } catch (Exception $e) {
                    Log::error("UserAPIController:registrationPromoSms Sms failure:".$e->getMessage());
                }*/
            if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                $user->addMedia(public_path('images/avatar_default_temp.png'))
                    ->withCustomProperties(['uuid' => bcrypt(str_random())])
                    ->toMediaCollection('avatar');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        Log::debug("UserAPIController.register :: End");

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
                'minimum_order' => '',
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
                'global_notes' => '',
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
    //  die('123');
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
            $this->validate($request, ['phone' => 'required|regex:/^[0-9]{10}$/|min:10']);
            $user = $this->userRepository->findByField('mobile', $request->input('phone'))->first();
             if (!$user) {
               return $this->sendError('Phone number is not registered with us.', 401);
            }
            $this->smsController->sendUserOTPSms($user);
             return $this->sendResponse($user,'User retrieved successfully');
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

            return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
        }

        public function validateMobileOtp(Request $request)
        {
          $input = $request->all();
          $userId = $input['userId'];
          $userOtp = $input['userOtp'];
          $newTime = strtotime('-2 minutes');
          $user = User::select('*')
                ->where('id', '=', $userId)
                ->where('mobile_otp', '=', $userOtp)
               // ->where('updated_at', '=', date('Y-m-d H:i:s', $newTime))
                ->first();
          if (empty($user)) {
                  return $this->sendResponse([
                                             'error' => true,
                                             'code' => 404,
                                         ], 'User not found');
           }
           $user->mobile_otp = "";
           $user->mobile_verify = "1";
           $user->status = "1";
           $user->save();
           return $user;
        }      
        
        public function resendUserOTPSms($userId){     
            $user = $this->userRepository->findWithoutFail($userId);
            if (empty($user)) {
                return $this->sendResponse([
                                            'error' => true,
                                            'code' => 404,
                                        ], 'User not found');
            }       
            $this->smsController->sendUserOTPSms($user);
            return $user;
        }

        private function registrationPromoSms($mobile){

            $couponCode ="20";
            $message = "Welcome to DesiSabji.com. Cash Coupons worth Rs. ".$couponCode." have been added to your DesiSabji account. Please check your Emails for further details.";
            $this->smsController->registrationPromoSms($mobile,$message);
        }
}
