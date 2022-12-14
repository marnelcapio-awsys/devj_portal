<?php

namespace App\Rules;

use App\Models\Employees;
use Illuminate\Contracts\Validation\Rule;

class AccountStatus implements Rule
{
    private $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $employee = Employees::where('email', $value)->first();

        if(!$employee['active_status']){
            switch ($employee['approved_status']){
                case config('constants.APPROVED_STATUS_REJECTED'):     //rejected registration
                    $this->message = 'Your account has been rejected. Please check your email for more info.';
                    break;
                case config('constants.APPROVED_STATUS_PENDING'):     //pending registration
                    $this->message = 'Your account is still pending for approval.';
                    break;
                default:    //account has been deactivated 
                    $this->message = 'Your account has been deactivated. Please check it with your manager or admin.';
            }
            return false;
        }else{
            if($employee['approved_status'] === config('constants.APPROVED_STATUS_REJECTED') || $employee['approved_status'] === config('constants.APPROVED_STATUS_PENDING')){
                $this->message = 'Your account is not available. Please check it with your manager or admin.';
            }else{
                return true;    
            }
        }
    }  

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
