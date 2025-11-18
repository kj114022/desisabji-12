<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:54

 *
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlockedIp;
use App\Models\SignUpAudit;
use App\Repositories\UploadRepositoryInterface;
use App\Repositories\RegisterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;

use App\Models\Registration;
use Illuminate\Support\Facades\Validator;

class SignUpAPIController extends Controller
{
    private $registerRepository;
    private $uploadRepository;
    public function __construct(
        RegisterRepositoryInterface $registerRepository,
        UploadRepositoryInterface $uploadRepository
    ) {
        $this->registerRepository = $registerRepository;
        $this->uploadRepository = $uploadRepository;
    }
    
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request)
    {
        Log::debug('Inside SignUpAPIController::signUp method');
        $validator = Validator::make($request->all(), [
             'mobile' => 'required|unique:registrations',
        ]);

        if($validator->fails()) {
            Log::error('Mobile number already exists: ' . $validator->errors()->first('mobile'));
            // return to login screen
            return response()->json(['error' => 'Mobile number already exists'], 400);
        }
        $data = $request->validate([
             'name' => 'required',
             'password' => 'required|string|min:8',
             'email' => 'nullable|email',
             'mobile' => 'required|unique:registrations,mobile',
             'city_id' => 'required|exists:cities,id',
        ]);

        Log::debug('Validated data: ' . json_encode($data));
        $user = new Registration();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'];
        $user->city_id = $data['city_id'];
        $user->password_hash = Hash::make($data['password']);
        $user->ip_address = $ipAddress = $request->ip();


        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            Log::error('Invalid IP address: ' . $ipAddress);
            return response()->json(['error' => 'Invalid IP address'], 400);            
        }
        // Check if the IP address is blocked
        $blocked = BlockedIp::where('ip_address', $ipAddress)->exists();
        if ($blocked) {
            Log::warning('Blocked IP address attempted registration: ' . $ipAddress);
            return response()->json(['error' => 'IP address blocked'], 403);
        } else {
            // send mobile otp 
            $otp = $this->registerRepository->generateNumericOTP(6, $data['mobile'], $user);
            $user->mobile_otp = $otp;
            Log::info('Generated OTP for mobile: ' . $data['mobile'] . ' is ' . $otp);
            // send sms
            $result = $this->registerRepository->sendSms($data['mobile'], 'Your OTP for registration is ' . $otp);
            if (!$result) { 
                Log::error('Failed to send SMS to mobile: ' . $data['mobile']);
                return response()->json(['error' => 'Failed to send SMS'], 500);
            }
            $user->save();
            Log::info('User registered successfully: ' . $user->id);
            // Log the registration attempt
            $signUpAudit = new SignUpAudit();
            $signUpAudit->ip_address = $ipAddress;
            $signUpAudit->device_info = $request->header('User-Agent');
            $signUpAudit->user_id = $user->id;
            $signUpAudit->save();
            Log::info('SignUpAudit logged successfully for user: ' . $user->id);
        }
        
        if($user->email != null){
               $this->sendEmailForOtp($otp, $user);
               Log::info('Email sent to user: ' . $user->email);
        }

        $response = [
            'registration_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'mobile_otp' => $user->mobile_otp,
            'otp_expires_at' => $user->otp_expires_at,
            'city_id' => $user->city_id,
        ];

        return $this->sendResponse($response, 'User registered successfully');

    }

    public function sendEmailForOtp($otp,$user)
    {
        $data = [
            'name' => $user->name,
            'message' => 'Your OTP for registration is ' . $otp
        ];

        Log::info('Preparing to send email with data: ' . json_encode($data));
        $this->registerRepository->sendEmail($data);
        return response()->json(['success' => 'Email sent successfully.']);
    }

}