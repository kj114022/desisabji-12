<?php
/**
 * File name: NotificationAPIController.php
 * Last modified: 2020.05.07 at 10:41:01
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;
/**
 * Class SMSAPIController
 * @package App\Http\Controllers\API
 */
class SMSAPIController extends Controller
{
    
    public function __construct()
    {
        
    }

    public function referralSms($mobile,$refAmt1,$refAmt2,$refCount){
       
        Log::debug('SMSAPIController::referralSms Started');
        $sender = "500232"; // This is who the message appears to be from.
        $message = "Refer your friends and family, give them ".$refAmt1." towards their ".$refCount." order by your referral code. You'll get ".$refAmt2.". Desi Sabji (sponsored by ALGOLIVE)";
        $message = urlencode($message);
         $this->smsServiceCall($sender,$message,$mobile);
         Log::debug('SMSAPIController::referralSms End');
   }

   public function orderConfirmationSms($mobile,$message){
   
    Log::debug('SMSAPIController::orderConfirmationSms ');
    $sender = "DSJOTP"; // This is who the message appears to be from.
    $message = urlencode($message);
    $this->smsServiceCall($sender,$message,$mobile);
     Log::debug('SMSAPIController::referralSms end ');
  }

  public function registrationPromoSms($mobile,$message){
   
    Log::debug('SMSAPIController::registrationPromoSms started');
    $sender = "500232"; 
    $message = urlencode($message);
   // $this->smsServiceCall($sender,$message,$mobile);
   $this->whatsappServiceCall($sender,$message,$mobile);
   
    Log::debug('SMSAPIController::registrationPromoSms end');
  }
    
    public function sendUserOTPSms(User $user){
            Log::debug('SMSAPIController::sendUserOTPSms started');
            $otp = $this->generateNumericOTP(6,$user);
            $username = "algoliveindia@gmail.com";
    		$hash = "bd6b11dd7b6496265c67209446e1cdabf4f2cc0942f31587256334bd6c950446";
    		$apiKey = urlencode('i0ELDPl2d2c-Cl26cFqWCLNsr6O7nagAYi3onFyEmY');
    		$test = "0";
    		$sender = "DSJOTP"; // This is who the message appears to be from.
    		$message = "Welcome to DesiSabji. Your OTP for mobile verification is ".$otp.". Thanks, DesiSabji.";
    		$message = urlencode($message);
    		$this->smsServiceCall($sender,$message,$user->mobile);
    		Log::debug('SMSAPIController::sendUserOTPSms end');
    }

     private function generateNumericOTP($otpLength,User $user) {

    		$generator = "1287903465";
    		$result = "";
    		for ($i = 1; $i <= $otpLength; $i++) {
    			$result .= substr($generator, (rand()%(strlen($generator))), 1);
    		}
            if (empty($user)) {
                  return $this->sendResponse([
                            'error' => true,
                            'code' => 404,
                        ], 'User not found');
            }
            try {
                 $user->mobile_otp = $result;
                 $user->save();
            } catch (ValidatorException $e) {
                        return $this->sendError($e->getMessage(), 401);
            }

            return $result;
    	}

       private function smsServiceCall($sender,$message,$mobile){
            Log::debug('SMSAPIController::smsServiceCall start ');
            $hash = "bd6b11dd7b6496265c67209446e1cdabf4f2cc0942f31587256334bd6c950446";
            $username = "algoliveindia@gmail.com";
            $apiKey = urlencode('i0ELDPl2d2c-Cl26cFqWCLNsr6O7nagAYi3onFyEmY');
            $data = array('apikey' => $apiKey, 'numbers' => $mobile, "sender" => $sender, "message" => $message);
            $ch = curl_init('http://api.textlocal.in/send/?');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch); 
            curl_close($ch);
            Log::debug('SMSAPIController::smsServiceCall end ');
       }


       private function whatsappServiceCall($sender,$message,$mobile){
        Log::debug('SMSAPIController::whatsappServiceCall start ');
        $hash = "bd6b11dd7b6496265c67209446e1cdabf4f2cc0942f31587256334bd6c950446";
        $username = "algoliveindia@gmail.com";
        $apiKey = urlencode('i0ELDPl2d2c-Cl26cFqWCLNsr6O7nagAYi3onFyEmY');
        $messages = array(
          // Put parameters here such as force or test
          'send_channel' => 'whatsapp',
          'messages' => array(
              array(
                  'number' => 918826708168,
                  'template' => array(
                      'id' => '12345',
                      'merge_fields' => array(
                          'FirstName' => 'Aleisha',
                          'LastName' => 'Britt',
                          'Custom1' => 'Custom 1 field test',
                          'Custom2' => 'Custom 2 field test',
                          'Custom3' => 'Custom 3 field test',
                      )
                  ),
                  
              )
          )
      );
       
      // Prepare data for POST request
      $data = array(
          'apikey' => $apiKey,
          'data' => json_encode($messages)
      );
       
      // Send the POST request with cURL
      $ch = curl_init('https://api.textlocal.in/bulk_json/');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      curl_close($ch);
       
      echo $response;
       
        Log::debug('SMSAPIController::whatsappServiceCall end ');
   }

}
