<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaptopsRequest;
use App\Mail\Laptops as MailLaptops;
use App\Models\Employees;
use App\Models\Laptops;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LaptopsController extends Controller
{
    public function create($rejectCode = ""){
        $laptop = '';
        if($rejectCode){
            $laptop = Laptops::where('reject_code', $rejectCode)
                ->where('approved_status', 3)
                ->where('status',1)
                ->first();
            abort_if(empty($laptop), 404);
        }

        return view('laptops.create')->with(['laptop' => $laptop]);
    }

    public function regist(LaptopsRequest $request){
        $request-> validated();
        
        $data = $request->except(['_token']);
        $data['status'] = 1;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        
        if(empty($data['id'])){
            //new registration
            unset($data['id']);
            if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
                //approve the registration, no email is sent
                $data['approved_status'] = config('constants.APPROVED_STATUS_APPROVED');
                $data['approved_by'] = Auth::user()->id;

                $id = Laptops::create($data)->id;

            }else{
                //pending request, 
                $data['approved_status'] = config('constants.APPROVED_STATUS_PENDING');
                $id = Laptops::create($data)->id;

            }
        }else{
            //registration update
            $id = $data['id'];
            unset($data['id']);
            $data['approved_status'] = config('constants.APPROVED_STATUS_PENDING');
            $data['reject_code'] = NULL;
            $data['reasons'] = NULL;

            Laptops::where('id', $id)
                    ->update($data);
        }

        //create logs
        Logs::createLog("Laptop", 'Laptop Registration');

        //send mail to managers
        $recipients = Employees::getEmailOfManagers();

        $mailData = [
            'link' => "/laptops/{$id}/request",
            'currentUserId' => Auth::user()->id,
            'module' => "Laptop",
        ];

        Mail::to($recipients)->send(new MailLaptops($mailData, config('constants.MAIL_LAPTOP_NEW_REGISTRATION_REQUEST')));
        
        return redirect(route('laptops.regist.complete'));
    }
}
