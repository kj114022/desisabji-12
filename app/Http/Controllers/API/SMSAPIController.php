<?php
/**
 * File name: NotificationAPIController.php
 * Last modified: 2020.05.07 at 10:41:01

 *
 */

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Registration;
use App\Repositories\Eloquent\UserRepository;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Eloquent\RegisterRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RegisterRepositoryInterface;

/**
 * Class SMSController
 * @package App\Http\Controllers\API
 */
class SMSAPIController extends Controller
{
    /** @var  UserRepository */
    private $userRepository;
    /** @var  RegisterRepository */
    private $registerRepository;

    public function __construct(UserRepositoryInterface $userRepository, RegisterRepositoryInterface $registerRepository)
    {
        $this->userRepository = $userRepository;
        $this->registerRepository = $registerRepository;
    }

    /**
     * API to resend OTP SMS to user, /resend_user_otp_sms
     * Resend OTP SMS to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOTPSms(Request $request){
         $input = $request->all();
         $user= null;
         $isRegistration = $input['isRegistration'] ?? false; // Check if it's for registration
         try {
                Log::debug('SMSAPIController resendOTPSms input :'.json_encode($input));
                $validator = Validator::make($request->all(), [
                            'mobile' => 'required|numeric|regex:/^[0-9]{10}$/',
                    //        'userId' => 'required|numeric',
                        ]);

                        if($validator->fails()) {
                            Log::error('Validation failed: ' . json_encode($validator->errors()));
                            return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 400);
                        }
                $data = $validator->validated();
                if($isRegistration){
                    $user = Registration::select('*')
                        ->where('mobile', '=', $data['mobile'])
                        ->first();
                        if (empty($user)) {
                            return $this->sendResponse([
                                                        'error' => true,
                                                        'code' => 404,
                                                    ], 'User not found');
                        }
                        $otp = $this->registerRepository->generateNumericOTP(6, $user->mobile, $user);
                        $this->sendEmailForResndOtp($otp, $user);
                } else {
                    // For existing user verification
                    // Check if the user exists and the OTP matches
                    $user = User::select('*')
                            ->where('mobile', '=', $data['mobile'])
                            ->first();
                             if (empty($user)) {
                                return $this->sendResponse([
                                                            'error' => true,
                                                            'code' => 404,
                                                        ], 'User not found');
                            }
                            $otp = $this->userRepository->generateNumericOTP(6, $user->mobile, $user);
                            $this->sendEmailForResndOtp($otp, $user);
                }
              
                // Send OTP SMS to user
               // $this->sendUserOTPSms($otp,$mobile);
               $response = [
                    'message' => 'OTP sent successfully',
                    'otp_expires_at' => Carbon::now()->addMinutes(2),
                    'otp' => $otp,
                    'mobile' => $user->mobile,
                    'userId' => $data['userId'],
                ];
                return $response;
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
    }

    public function resendUserOTPSms($userId,$mobile){
         $input = $request->all();
         try {
                Log::debug('SMSAPIController resendUserOTPSms input :'.json_encode($input));
         
                if (empty($userId) || empty($mobile)) {
                    return $this->sendResponse([
                                                    'error' => true,
                                                    'code' => 400,
                                                ], 'User ID and mobile number are required');
                }
        
                $user = $this->userRepository->findWithoutFail($userId);
                if (empty($user)) {
                    return $this->sendResponse([
                                                'error' => true,
                                                'code' => 404,
                                            ], 'User not found');
                }
                $otp = $this->userRepository->generateNumericOTP(6, $mobile, $user);
                $user->mobile_otp = $otp;
                $user->otp_expires_at = Carbon::now()->addMinutes(2);
                $user->save();
                // Send OTP SMS to user
               // $this->sendUserOTPSms($otp,$mobile);
               $response = [
                    'otp' => $otp,
                    'message' => 'OTP sent successfully',
                    'otp_expires_at' => $user->otp_expires_at,
                    'mobile' => $mobile,
                    'userId' => $userId,
                ];
                return $response;
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
    }

    public function sendUserOTPSms($otp,$numbers){
            $username = "algoliveindia@gmail.com";
    		$hash = "bd6b11dd7b6496265c67209446e1cdabf4f2cc0942f31587256334bd6c950446";
    		$apiKey = urlencode('i0ELDPl2d2c-Cl26cFqWCLNsr6O7nagAYi3onFyEmY');
    		$test = "0";
    		$sender = "DSJOTP"; // This is who the message appears to be from.
    		$message = "Welcome to DesiSabji. Your OTP for mobile verification is ".$otp.". Thanks, DesiSabji.";
    		$message = urlencode($message);
    		$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
    		$ch = curl_init('http://api.textlocal.in/send/?');
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$result = curl_exec($ch); // This is the result from the API
    		curl_close($ch);
    		 Log::debug('User sms send');
    }

    /**
     * API to verify mobile OTP, /verify_mobile
     * Check mobile OTP
     *
     * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function mobileOtpCheck(Request $request)
        {
          Log::debug('SMSAPIController mobileOtpCheck :: '.$request->get('mobile'));
          $user = null;
          $input = $request->all();
          $mobile = $input['mobile'];
          $userOtp = $input['userOtp'];
          $isRegistration = $input['isRegistration'] ?? false;
          Log::debug('SMSAPIController mobileOtpCheck :: '.$mobile.'  otp :: '.$userOtp);

          if (empty($mobile) || empty($userOtp)) {
              return $this->sendResponse([
                                             'error' => true,
                                             'code' => 400,
                                         ], 'Mobile number and OTP are required');
          }
         if (!is_numeric($mobile) || !is_numeric($userOtp)) {
                return $this->sendResponse([
                                                 'error' => true,
                                                 'code' => 400,
                                             ], 'Mobile number and OTP must be numeric');
         }
         try {
                    if ($isRegistration) {
                            $user = Registration::select('*')
                                ->where('mobile', '=', $mobile)
                                ->first();
                            Log::debug('SMSAPIController user '.$user);
                            
                    } else {
                            // For existing user verification
                            // Check if the user exists and the OTP matches
                            $user = User::select('*')
                                    ->where('mobile', '=', $mobile)
                                    ->first();
                                Log::debug('SMSAPIController user '.$user);
                    }
                        // Check if the user exists and the OTP matches
                    if (empty($user)) {
                                    return $this->sendResponse([
                                                                'error' => true,
                                                                'code' => 404,
                                                            ], 'User not found');
                                                        }
                    if (empty($user->mobile_otp) || $user->mobile_otp != $userOtp) {
                            return $this->sendResponse([
                                                            'error' => true,
                                                            'code' => 400,
                                                        ], 'Please enter valid otp. If otp is expired, please reset again');
                    }
                    if ($user->otp_expires_at <= date('Y-m-d H:i:s')) {
                            return $this->sendResponse([    
                                                            'error' => true,
                                                            'code' => 400,
                                                        ], 'OTP has expired');      
                    } else {
                            // OTP is valid, update the user record     
                                $user->mobile_otp = "";
                                $user->mobile_verify = "1";           
                                $user->status = "1";
                                $user->save();
                                // confirm user registration
                                if ($isRegistration) {
                                        $newUser = new User();
                                        $newUser->name = $user->name;
                                        $newUser->email = $user->email;
                                        $newUser->mobile = $user->mobile;
                                        $newUser->password = $user->password_hash;
                                        $newUser->status = '1';
                                        $newUser->mobile_verify = '1';
                                        $newUser->login_id = $user->mobile;
                                        $newUser->customer_id = 'C'.$user->mobile;
                                        $newUser->city_id = $user->city_id;
                                        //set the api_token
                                        // set device_token
                                        $newUser->save();

                                }     
                                $response = [
                                    'message' => 'Mobile number verified successfully',
                                    'user_id' => $user->id,
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'mobile' => $user->mobile,
                                    'mobile_verify' => (bool)$user->mobile_verify,
                                    'status' => $user->status,
                                    'city_id' => $user->city_id,
                                ];
                            return $response;
                    }
                 } catch (\Exception $e) {
                    Log::error('SMSAPIController mobileOtpCheck error: ' . $e->getMessage());
                    return $this->sendError($e->getMessage(), 500);
                }
        }


    public function sendEmailForResndOtp($otp,$user)
        {
            $data = [
                'name' => $user->name,
                'message' => 'Your Resend OTP is ' . $otp
            ];

            Log::info('Preparing to send email with data: ' . json_encode($data));
            $this->registerRepository->sendEmail($data);
            $response = [
                'success' => true,
                'message' => 'Email sent successfully.',
                'otp' => $otp,
                'userId' => $user->id,
                'mobile' => $user->mobile,
            ];
            return $response;
        }
    }