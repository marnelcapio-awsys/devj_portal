<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassword;
use App\Http\Requests\LaptopsRequest;
use App\Http\Requests\LinkLaptop;
use App\Http\Requests\LinkProject;
use App\Http\Requests\LaptopLinkage;
use App\Mail\Employee;
use App\Mail\Software;
use App\Mail\Laptops as MailLaptops;
use App\Models\Employees;
use App\Models\EmployeesLaptops;
use App\Models\EmployeesProjects;
use App\Models\ProjectSoftwares;
use App\Models\Laptops;
use App\Models\Logs;
use App\Models\Projects;
use App\Models\Softwares;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function changePassword(ChangePassword $request){
        
        $request->validated();
        $data = $request->only(['id', 'new_password']);

        //save new password
        Employees::where('id', $data['id'])
        ->update(['password' => password_hash($data['new_password'], PASSWORD_BCRYPT), 'updated_by' => Auth::user()->id]);

        Logs::createLog("Employee", "Updated password");

        return response()->json(['success' => true], 200);
    }

    public function linkLaptop(LinkLaptop $request){
        $request->validated();

        $data = $request->except(['_token', ]);

        $insertData = [
            'laptop_id' => $data['laptop_id'],
            'employee_id' => $data['employee_id'],
            'brought_home_flag' => $data['brought_home_flag'] ? 1 : 0,
            'vpn_flag' => $data['vpn_access_flag'] ? 1 : 0,
            'remarks' => $data['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];

        $employee = Employees::where('id', $data['employee_id'])->first();
        $laptop = Laptops::where('id', $data['laptop_id'])->first();

        //check logined employee role
        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
            //save directly in DB in db
            $insertData['approved_status'] =  config('constants.APPROVED_STATUS_APPROVED');
            $insertData['approved_by'] = Auth::user()->id;            
            EmployeesLaptops::create($insertData);

            if(Auth::user()->id != $data['employee_id']){
                $mailData = [
                    'link' => route('employees.details', ['id' => $employee->id]),
                    'first_name' => $employee->first_name,
                    'currentUserId' => Auth::user()->id,
                    'module' => "Employee",
                ];
                $this->sendMailForEmployeeUpdate($employee->email, $mailData, config('constants.MAIL_EMPLOYEE_LAPTOP_LINK_BY_MANAGER'));
            }
            $message = 'Added Successfully';
        }else{
            //if an employee edits his own data and is not the manager
            $insertData['approved_status'] = config('constants.APPROVED_STATUS_PENDING');

            EmployeesLaptops::create($insertData);

            //notify the managers of the request
            $mailData = [
                'link' => route('laptops.details', ['id' => $data['laptop_id']]) . '#link-req-tbl',
                'requestor' => Auth::user()->first_name .' ' .Auth::user()->last_name,
                'currentUserId' => Auth::user()->id,
                'module' => "Employee",
            ];

            $this->sendMailForEmployeeUpdate(Employees::getEmailOfManagers(), $mailData, config('constants.MAIL_EMPLOYEE_LAPTOP_LINK_REQUEST'));
            $message = 'Your request has been sent';
        }

        Logs::createLog("Employee", "Link {$employee->first_name} {$employee->last_name} to {$laptop->tag_number} laptop");

        return response()->json(['success' => true, 
                                    'message' => $message, 
                                    'update' => EmployeesLaptops::getOwnedLaptopByEmployee($data['employee_id'])]
                                , 200);
    }
    

    public function softwarelinkProject(LinkProject $request){
        $request->validated();


        //save data in db
        $data = $request->except(['_token', ]);

        $insertData = [
            'project_id' => $data['project_id'],
            'software_id' => $data['software_id'],
            'reasons' => $data['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];

        $software = Softwares::where('id', $data['software_id'])->first();
        $project = Projects::where('id', $data['project_id'])->first();

        $message = '';
        //check logined employee role
        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
            //save directly in DB in db
            $insertData['approved_status'] =  config('constants.APPROVED_STATUS_APPROVED');
            $insertData['approved_by'] = Auth::user()->id;        

            ProjectSoftwares::create($insertData);
            $message = 'Added Successfully';
        }else{
            //if an employee edit sofwtare data and not the manager
            $insertData['approved_status'] = config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE');

            ProjectSoftwares::create($insertData);

            //notify the managers of the request
            $mailData = [
                'link' => route('softwares.request', ['id' => $software->id]),
                'requestor' => Auth::user()->first_name .' ' .Auth::user()->last_name,
                'currentUserId' => Auth::user()->id,
                'module' => "Software",
            ];

            $this->sendMailForSoftwareUpdate(Employees::getEmailOfManagers(), $mailData, config('constants.MAIL_SOFTWARE_PROJECT_LINK_REQUEST'));
            $message = 'Your request has been sent';
        }
        
        Logs::createLog("Software", "Link {$software->software_name} to {$project->name}");
        return response()->json(['success' => true, 
                                    'message' => $message, 
                                    'update' => ProjectSoftwares::getProjectBySoftware($data['software_id'])]
                                , 200);
    }

    public function linkEmployeeProject(LinkProject $request){
        $request->validated();

        //save data in db
        $data = $request->except(['_token', ]);

        $insertData = [
            'project_id' => $data['project_id'],
            'employee_id' => $data['employee_id'],
            'start_date' => $data['project_start'],
            'end_date' => $request->filled('project_end') ? $data['project_end'] : NULL,
            'project_role_type' => $data['project_role'],
            'onsite_flag' => $data['project_onsite'] ? 1 : 0,
            'remarks' => $data['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];

        $employee = Employees::where('id', $data['employee_id'])->first();
        $project = Projects::where('id', $data['project_id'])->first();

        $message = '';       
        //check logined employee role
        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
            //save directly in DB in db
            $insertData['approved_status'] =  config('constants.APPROVED_STATUS_APPROVED');
            $insertData['approved_by'] = Auth::user()->id;        

            EmployeesProjects::create($insertData);

            if(Auth::user()->id != $data['employee_id']){
                $mailData = [
                    'link' => route('employees.details', ['id' => $employee->id]),
                    'first_name' => $employee->first_name,
                    'currentUserId' => Auth::user()->id,
                    'module' => "Employee",
                ];
                $this->sendMailForEmployeeUpdate($employee->email, $mailData, config('constants.MAIL_EMPLOYEE_PROJECT_LINK_BY_MANAGER'));
            }
            $message = 'Added Successfully';
        }else{
            //if an employee edits his own data and is not the manager
            $insertData['approved_status'] = config('constants.APPROVED_STATUS_PENDING');

            EmployeesProjects::create($insertData);

            //notify the managers of the request
            $mailData = [
                'link' => "/",  //update link
                'requestor' => Auth::user()->first_name .' ' .Auth::user()->last_name,
                'currentUserId' => Auth::user()->id,
                'module' => "Employee",
            ];

            $this->sendMailForEmployeeUpdate(Employees::getEmailOfManagers(), $mailData, config('constants.MAIL_EMPLOYEE_PROJECT_LINK_REQUEST'));
            $message = 'Your request has been sent';
        }
        
        Logs::createLog("Employee", "Link {$employee->first_name} {$employee->last_name} to {$project->name}");

        return response()->json(['success' => true, 
                                    'message' => $message, 
                                    'update' => EmployeesProjects::getProjectsByEmployee($data['employee_id'])]
                                , 200);
    }

    /**
     * send email
     * @param array $mailData
     * @param int $mailType
     * @return void
     */
    private function sendMailForEmployeeUpdate($recipients, $mailData, $mailType){
        Mail::to($recipients)->send(new Employee($mailData, $mailType));
    }

        /**
     * sendMailForSoftwareUpdate
     * @param array $mailData
     * @param int $mailType
     * @return void
     */
    private function sendMailForSoftwareUpdate($recipients, $mailData, $mailType){
        if (!empty($recipients)) {
            Mail::to($recipients)->send(new Software($mailData, $mailType));
        } 
    }

    public function getEmployeeByFilter(Request $request){
        $searchFilter = [
            'keyword' => $request->get('keyword'),
            'filter' => $request->get('filter'),
            'status' => $request->get('status'),
        ];
        $employee = Employees::whereIn('approved_status', [2,4]);
                    
       // get employees
        if (!empty($searchFilter['keyword'])) {
            if ($searchFilter['filter'] == 1) {
                $employee = $employee->where(function($query) use ($searchFilter) {
                        $query->where('first_name','LIKE','%'.$searchFilter['keyword'].'%')
                                ->orWhere('last_name','LIKE','%'.$searchFilter['keyword'].'%')
                                ->orWhere('middle_name','LIKE','%'.$searchFilter['keyword'].'%');
                    });
            } else if ($searchFilter['filter'] == 2) {
                $employee = $employee->where('current_address_city','LIKE','%'.$searchFilter['keyword'].'%');
            } else if ($searchFilter['filter'] == 3) {
                $employee = $employee->where('current_address_province','LIKE','%'.$searchFilter['keyword'].'%');
            }
        }

        if (Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
            if ($searchFilter['status'] != 1) {
                if ($searchFilter['status'] == 4) {
                    $employee = $employee->where('bu_transfer_flag', 1);
                }else{
                    $status = $searchFilter['status'] == 2 ? 1 : 0;
                    $employee = $employee->where('active_status', $status);
                }
            }
        }else{
            $employee = $employee->where('active_status', 1);
        }

        $employee = $employee->orderBy('last_name', 'ASC')
                ->get();

        return json_encode($employee);
    }

    public function getSoftwareByFilter(Request $request){
        $searchFilter = [
            'keyword' => $request->get('keyword'),
            'status' => $request->get('status'),
            'type' => $request->get('type'),
        ];
        $software = Softwares::whereIn('approved_status', [config('constants.APPROVED_STATUS_REJECTED'),
                                                            config('constants.APPROVED_STATUS_APPROVED'),
                                                            config('constants.APPROVED_STATUS_PENDING'),
                                                            config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE')]);

        // get software
        if (!empty($searchFilter['keyword'])) {
            $software = $software->where('software_name','LIKE','%'.$searchFilter['keyword'].'%');
        }
        
        if(!empty($searchFilter['status']))
        {
            if($searchFilter['status'] != config('constants.SOFTWARE_FILTER_STATUS_ALL'))//status choses is all
            {
                $software = $software->where('approved_status','LIKE','%'.$searchFilter['status'].'%');
            }
        }
        if(!empty($searchFilter['type']))
        {
            if($searchFilter['type'] != config('constants.SOFTWARE_FILTER_TYPE_ALL'))//status choses is all
            {
                $software = $software->where('type','LIKE','%'.$searchFilter['type'].'%');
            }

        }
        $software = $software->orderBy('software_name', 'ASC')
                ->get();

        return json_encode($software);
    }

    public function filterLaptopList(Request $request){
        $data = $request->all();

        $laptopList = Laptops::getLaptopList($data['keyword'], $data['availability'], $data['status'], $data['searchFilter']);

        return response()->json([
                                'success' => true,
                                'update' => $laptopList
        ]);
    }

    /**
     * Laptop Detail Update Process
     *
     * @param LaptopsRequest $request
     * @return void
     */
    public function updateLaptopDetails(LaptopsRequest $request){
        $request->validated();

        $updateData = $request->except(['_token', 'isUpdate', 'edit_id']);
        if(!isset($updateData)){
            $updateData['status'] = 0;
        }
        $id = $request->input('edit_id');
        $originalData = Laptops::where('id', $id)->first();
        $dbReadyData = [];
        foreach($updateData as $key => $val){
            if($originalData[$key] != $val){
                $dbReadyData[$key] = $val;
            }
        }
        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){
            //format log
            $log = 'Laptop Update: ';
            foreach($dbReadyData as $key => $val){
                $log .= "{$key}: {$originalData[$key]} > {$val}, ";
            }
            $log = rtrim($log, ", ");

            //update data in DB
            $dbReadyData['updated_by'] = Auth::user()->id;
            $dbReadyData['approved_status'] = config('constants.APPROVED_STATUS_APPROVED');
            $dbReadyData['approved_by'] = Auth::user()->id;
            Laptops::where('id', $id)
                    ->update($dbReadyData);

            Logs::createLog('Laptop', $log);

            session(['l_alert'=> 'Laptop detail was updated successfully.']);
        }else{
            Laptops::where('id', $id)
                    ->update([
                        'approved_status' => config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE'),
                        'update_data' => json_encode($dbReadyData, true),
                        'updated_by' => Auth::user()->id
                    ]);
            Logs::createLog('Laptop', 'Laptop Update: ' .json_encode($dbReadyData));

            //send mail
            $recipients = Employees::getEmailOfManagers();

            $mailData = [
                'link' => "/laptops/{$id}/request",
                'currentUserId' => Auth::user()->id,
                'module' => "Laptop",
            ];

            $this->sendMailForLaptop($recipients, $mailData, config('constants.MAIL_LAPTOP_DETAIL_UPDATE_REQUEST'));

            session(['l_alert'=> 'Request for Laptop Detail Update has been sent.']);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Laptop  Linkage Detail Update Process
     *
     * @param LaptopLinkage $request
     * @return void
     */
    public function updateLaptopLinkage(LaptopLinkage $request){
        $request->validated();
        
        $updateData = $request->except(['_token', 'id']);
        $id = $request->input('id');

        $originalData = EmployeesLaptops::where('id', $id)->first();
        $dbReadyData = [];
        foreach($updateData as $key => $val){
            if($originalData[$key] != $val){
                $dbReadyData[$key] = $val;
            }
        }

        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){

            //format log
            $log = 'Laptop Linkage Update: ';
            foreach($dbReadyData as $key => $val){
                $log .= "{$key}: {$originalData[$key]} > {$val}, ";
            }
            $log = rtrim($log, ", ");

            //update data in DB
            $dbReadyData['updated_by'] = Auth::user()->id;
            $dbReadyData['approved_status'] = config('constants.APPROVED_STATUS_APPROVED');
            $dbReadyData['approved_by'] = Auth::user()->id;
            EmployeesLaptops::where('id', $id)
                    ->update($dbReadyData);

            Logs::createLog('Laptop', $log);

            $recipient = Employees::where('id', $originalData['employee_id'])->first();

            if($recipient->id != Auth::user()->id){
                //send mail
                $mailData = [
                    'link' => "/laptops/{$originalData['laptop_id']}",
                    'firstName' => $recipient['first_name'],
                    'currentUserId' => Auth::user()->id,
                    'module' => "Laptop",
                ];
    
                $this->sendMailForLaptop($recipient['email'], $mailData, config('constants.MAIL_LAPTOP_LINKAGE_UPDATE_BY_MANAGER_NOTIF'));
            }

            session(['ul_alert'=> 'Laptop linkage was updated successfully.']);
        }else{
            EmployeesLaptops::where('id', $id)
                    ->update([
                        'approved_status' => config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE'),
                        'update_data' => json_encode($dbReadyData, true),
                        'updated_by' => Auth::user()->id
                    ]);
            Logs::createLog('Laptop', 'Latop Linkage Update: ' .json_encode($dbReadyData));

            //send mail
            $recipients = Employees::getEmailOfManagers();

            $mailData = [
                'link' => "/laptops/{$originalData['laptop_id']}",
                'currentUserId' => Auth::user()->id,
                'module' => "Laptop",
            ];

            $this->sendMailForLaptop($recipients, $mailData, config('constants.MAIL_LAPTOP_LINKAGE_UPDATE_BY_NON_MANAGER_REQUEST'));

            session(['ul_alert'=> 'Request for Laptop Linkage Update has been sent.']);
        }
        

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Registration of new laptop linkage
     *
     * @param LaptopLinkage $request
     * @return void
     */
    public function registLaptopLinkage(LaptopLinkage $request){
        $request->validated();
        
        $requestData = $request->except(['_token']);
        $laptopData = Laptops::where('id', $requestData['id'])->first();
        $employeeData = Employees::where('id', $requestData['assignee'])->first();

        $insertData = [
            'laptop_id' => $requestData['id'],
            'employee_id' => $requestData['assignee'],
            'brought_home_flag' => $requestData['brought_home_flag'],
            'remarks' => $requestData['remarks'],
            'vpn_flag' => $requestData['vpn_flag'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];

        if(Auth::user()->roles == config('constants.MANAGER_ROLE_VALUE')){

            //add data
            $insertData['approved_status'] = config('constants.APPROVED_STATUS_APPROVED');
            $insertData['approved_by'] = Auth::user()->id;
            EmployeesLaptops::create($insertData);

            Logs::createLog('Laptop', "{$laptopData['tag_number']} laptop is linked to {$employeeData['last_name']}, {$employeeData['first_name']}");

            if($employeeData->id != Auth::user()->id){
                //send mail
                $mailData = [
                    'link' => "/laptops/{$requestData['id']}",
                    'firstName' => $employeeData['first_name'],
                    'currentUserId' => Auth::user()->id,
                    'module' => "Laptop",
                ];
    
                $this->sendMailForLaptop($employeeData['email'], $mailData, config('constants.MAIL_LAPTOP_NEW_LINKAGE_BY_MANAGER_NOTIF'));
            }

            session(['ll_alert'=> 'Laptop was linked successfully.']);

            $this->rejectOtherLinkageRequest($requestData['id']);
        }else{
            //add data
            $insertData['approved_status'] = config('constants.APPROVED_STATUS_PENDING');
            EmployeesLaptops::create($insertData);

            Logs::createLog('Laptop', "{$employeeData['last_name']}, {$employeeData['first_name']} requests to use {$laptopData['tag_number']} laptop");

            //send mail
            $recipients = Employees::getEmailOfManagers();

            $mailData = [
                'link' => "/laptops/{$requestData['id']}",
                'currentUserId' => Auth::user()->id,
                'module' => "Laptop",
            ];

            $this->sendMailForLaptop($recipients, $mailData, config('constants.MAIL_LAPTOP_NEW_LINKAGE_BY_NON_MANAGER_REQUEST'));

            session(['ul_alert'=> 'Request for Laptop Linkage has been sent.']);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * send laptop related email
     * @param array $mailData
     * @param int $mailType
     * @return void
     */
    private function sendMailForLaptop($recipients, $mailData, $mailType){
        Mail::to($recipients)->send(new MailLaptops($mailData, $mailType));
    }

    private function rejectOtherLinkageRequest($laptop_id){
        $pendingApproval = EmployeesLaptops::getLinkRequestByLaptop($laptop_id, config('constants.APPROVED_STATUS_PENDING'));
        if(!empty($pendingApproval)){
            $reason = 'Laptop has been assigned to other employee';
            $pendingIds = array_column($pendingApproval, 'id');
            EmployeesLaptops::whereIn('id', $pendingIds)
                                ->update([
                                    'approved_status' => config('constants.APPROVED_STATUS_REJECTED'),
                                    'approved_by' => Auth::user()->id,
                                    'updated_by' => Auth::user()->id,
                                    'reasons' => $reason,
                                ]);
    
            Logs::createLog('Laptop', 'Laptop Linkage Request Rejection');
    
            //send mail
            foreach($pendingApproval as $request => $data){
                $mailData = [
                    'link' => route('laptops.details', ['id' => $laptop_id]),
                    'reason' => $reason,
                    'firstName' => $data['first_name'],
                    'currentUserId' => Auth::user()->id,
                    'module' => "Laptop",
                ];
                Mail::to($data['email'])->send(new MailLaptops($mailData, config('constants.MAIL_LAPTOP_NEW_LINKAGE_BY_NON_MANAGER_REJECTION')));
            }
        }
    }

    /**
     * Deactivate employee
     *
     * @param Request $request
     * @return void
     */
    public function deactivateEmployee(Request $request){
        $employeeId = $request->input('id');
        $message = '';
        $success = false;

        if(empty($employeeId)){
            $message = 'Invalid Request!';
        }else{
            $employee = Employees::where('id', $employeeId)
                                ->whereIn('approved_status', [config('constants.APPROVED_STATUS_APPROVED'), config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE')])
                                ->first();
            if(empty($employee)){
                $message = 'Invalid Request!';
            }elseif($employee->active_status == 0){
                $message = 'Employee has already been deactivated.';
            }else{
                //check if employee has no linked laptops
                $laptops = EmployeesLaptops::getOwnedLaptopByEmployee($employeeId);
                if(!empty($laptops)){
                    $notify = true;
                    $message = 'Deactivation is not allowed because laptop/s are still assigned to the employee.';

                    $mailData = [
                        'first_name' => $employee->first_name,
                        'laptops' => $laptops,
                        'module' => 'Employee',
                        'currentUserId' => Auth::user()->id,
                    ];

                }else{
                    $success = true;
                    //deactivate employee
                    Employees::where('id', $employeeId)
                                ->update([
                                    'active_status' => 0,
                                    'bu_transfer_flag' => 0,
                                    'bu_transfer_assignment' => NULL,
                                    'approved_status' => config('constants.APPROVED_STATUS_APPROVED'),
                                    'updated_by' => Auth::user()->id,
                                    'approved_by' => Auth::user()->id,
                                ]);
                    
                    Logs::createLog('Employee', "Deactivated {$employee->first_name} {$employee->last_name}'s account");
                    
                    $mailData = [
                        'first_name' => $employee->first_name,
                        'module' => 'Employee',
                        'currentUserId' => Auth::user()->id,
                    ];

                    $this->sendMailForEmployeeUpdate($employee->email, $mailData, config('constants.MAIL_EMPLOYEE_DEACTIVATION'));
                    session(['success' => $success, 'message'=> 'Employee was successfully deactivated.']);
                }
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function reactivateEmployee(Request $request){
        $employeeId = $request->input('id');
        $message = '';
        $success = false;

        if(empty($employeeId)){
            $message = 'Invalid Request!';
        }else{
            $employee = Employees::where('id', $employeeId)
                                ->whereIn('approved_status', [config('constants.APPROVED_STATUS_APPROVED'), config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE')])
                                ->first();
            if(empty($employee)){
                $message = 'Invalid Request!';
            }elseif($employee->active_status == 1){
                $message = 'Employee is still active';
            }else{
                $success = true;
                //reactivate employee

                Employees::where('id', $employeeId)
                            ->update([
                                'active_status' => 1,
                                'approved_status' => config('constants.APPROVED_STATUS_APPROVED'),
                                'updated_by' => Auth::user()->id,
                                'approved_by' => Auth::user()->id,
                            ]);
                
                Logs::createLog('Employee', "Reactivated {$employee->first_name} {$employee->last_name}'s account");
                

                $mailData = [
                    'first_name' => $employee->first_name,
                    'module' => 'Employee',
                    'currentUserId' => Auth::user()->id,
                ];

                $this->sendMailForEmployeeUpdate($employee->email, $mailData, config('constants.MAIL_EMPLOYEE_REACTIVATION'));
                session(['success' => $success, 'message'=> 'Employee was successfully reactivated.']);

            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function transferEmployee (Request $request) {
        $employeeId = $request->input('id');
        $message = '';
        $success = false;

        if(empty($employeeId)){
            $message = 'Invalid Request!';
        }else{
            $employee = Employees::where('id', $employeeId)
                                ->whereIn('approved_status', [config('constants.APPROVED_STATUS_APPROVED'), config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE')])
                                ->first();
            if(empty($employee)){
                $message = 'Invalid Request!';
            }elseif($employee->bu_transfer_flag == 1){
                $message = 'Employee has already been assigned to a different BU.';
            }else{
                $success = true;
                //transfer employee

                Employees::where('id', $employeeId)
                            ->update([
                                'bu_transfer_flag' => 1,
                                'bu_transfer_assignment' => $request->input('bu_transfer_assignment'),
                                'approved_status' => config('constants.APPROVED_STATUS_APPROVED'),
                                'updated_by' => Auth::user()->id,
                                'approved_by' => Auth::user()->id,
                            ]);
                
                Logs::createLog('Employee', "Assigned {$employee->first_name} {$employee->last_name} to a different BU ({$request->input('bu_transfer_assignment')})");

                session(['success' => $success, 'message'=> 'Account was successfully updated.']);
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function reinstateEmployee (Request $request) {
        $employeeId = $request->input('id');
        $message = '';
        $success = false;

        if(empty($employeeId)){
            $message = 'Invalid Request!';
        }else{
            $employee = Employees::where('id', $employeeId)
                                ->whereIn('approved_status', [config('constants.APPROVED_STATUS_APPROVED'), config('constants.APPROVED_STATUS_PENDING_APPROVAL_FOR_UPDATE')])
                                ->first();
            if(empty($employee)){
                $message = 'Invalid Request!';
            }elseif($employee->bu_transfer_flag == 0){
                $message = 'Employee is already assigned to Dev J.';
            }else{
                $success = true;
                //reinstate employee

                Employees::where('id', $employeeId)
                            ->update([
                                'bu_transfer_flag' => 0,
                                'bu_transfer_assignment' => NULL,
                                'approved_status' => config('constants.APPROVED_STATUS_APPROVED'),
                                'updated_by' => Auth::user()->id,
                                'approved_by' => Auth::user()->id,
                            ]);
                
                Logs::createLog('Employee', "Reassigned {$employee->first_name} {$employee->last_name} to Dev J");

                session(['success' => $success, 'message'=> 'Account was successfully updated.']);
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
