<?php

namespace App\Repositories\Eloquent;

use App\Models\Registration;
use App\Repositories\RegisterRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
/**
 * Class RegisterRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method Registration findWithoutFail($id, $columns = ['*'])
 * @method Registration find($id, $columns = ['*'])
 * @method Registration first($columns = ['*'])
*/
class RegisterRepository extends BaseRepository implements RegisterRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name',
        'email',
        'mobile',
        'password_hash',
        'mobile_otp',
        'otp_expires_at',
    ];

    /**
     * @var Model
     */
    protected $model;

       /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Registration $model)
    {
        $this->model = $model;
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Registration::class;
    }

    /**
     * Generate a numeric OTP
     *
     * @param int $length
     * @param string $mobile
     * @param Registration $registration
     * @return string
     */
    public function generateNumericOTP($length, $mobile, Registration $registration): string
    {
        // Generate a numeric OTP of specified length
        $otp = rand(pow(10, $length - 1), pow(10, $length) - 1);
        // Store the OTP in the registration model or session as needed
        $registration->mobile_otp = $otp;
        $newTime = strtotime('+2 minutes');
        //$registration->otp_expires_at = date('Y-m-d H:i:s', $newTime);
        $registration->otp_expires_at = Carbon::now()->addMinutes(2);
        $registration->save();
        return (string)$otp;
    }
    /**
     * Send SMS to the given mobile number
     *
     * @param string $mobile
     * @param string $message
     * @return bool
     */
    public function sendSms($mobile, $message): bool
    {
        // Implement SMS sending logic here
        // For example, using a third-party service like Twilio or Nexmo
        // Return true if the SMS was sent successfully, false otherwise
        // This is a placeholder implementation
        return true;
    }

    /**
     * Send email to the user
     *
     * @param string $otp
     * @param Registration $registration
     * @return void
     */
    public function sendEmail($data): void
    {
        // Implement email sending logic here
        // For example, using a service like Mailgun or SendGrid

         Mail::to('algoliveindia@gmail.com')->send(new SendEmail($data));
    }
}
