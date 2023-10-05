@php
    $userInfo = Auth::user();
@endphp

@include('header')
<link rel="stylesheet" href="{{ asset(mix('css/employee.min.css')) }}">
<script src="{{ asset(mix('js/employee.min.js')) }}" defer></script>
@include('headerMenu')
<div id="alert-div"></div>
@if (session()->pull('success')) 
	<div class="alert alert-success" role="alert">
        <span class="ms-2">{{ session()->pull('message') }}</span>
	</div>
@endif

<div class="container text-center ps-md-3 pe-md-3 pt-5">
    <div class="d-flex justify-content-between mb-2">
        <div class="row">
            <div class="text-primary text-start">
                @if (!empty($detailNote))
                <div>
                    <i class="bi bi-info-circle-fill"></i>&nbsp;{{ $detailNote }}
                </div>
                @endif
                @if (!empty($buTransferNote))
                <div>
                    <i class="bi bi-info-circle-fill"></i>&nbsp;{{ $buTransferNote }}
                </div>
                @endif
            </div>
            @if($employee['passport_isAlertDisplayed'])
                @if($employee["passport_isWarning"])
                    <div class="text-danger text-start">
                @else
                    <div class="text-primary text-start">
                @endif
                        <div>
                            @if($employee["passport_isWarning"])
                                <i class="bi bi-info-circle-fill"></i>
                            @else
                                <i class="bi bi-info-circle-fill"></i>
                            @endif
                            {{$employee['passport_message']}}
                        </div>
                    </div>
            @endif
        </div>
        
        <div class="">
            @if ($allowedToEdit)
            <a href="{{ route('employees.edit', ['id' => $employee->id]) }}" class="btn btn-primary" type="button">Edit</a>
            @endif
            @if($detailOnly && $userInfo->id == $employee->id)
            <button type="button" class="btn btn-success  ms-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal" >Change Password</button>
            @endif
            @if ($detailOnly && $userInfo->roles == config('constants.MANAGER_ROLE_VALUE'))
                @if ($employee->active_status == 0)
                    <button class="btn btn-success ms-2" id="employee-reactivate">Reactivate
                        <div id="react-deact-spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                            <span class="sr-only"></span>
                        </div>
                    </button>
                @else
                    <button class="btn btn-danger ms-2" id="employee-deactivate">Deactivate
                        <div id="react-deact-spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                            <span class="sr-only"></span>
                        </div>
                    </button>
                    @if ($employee->bu_transfer_flag == 0)
                        <button class="btn btn-warning ms-2" id="employee-transfer" data-bs-toggle="modal" data-bs-target="#buTransferModal">BU Transfer</button>
                        <div class="modal fade" tabindex="-1" id="buTransferModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-start">BU Assignment</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="p-2">
                                            <div id="bu_transfer_msg">
                                            </div>
                                            <form action="#" id="transferEmployeeForm">
                                                @csrf
                                                <input type="text" hidden name="id" value="{{ $employee->id }}">
                                                <div class="g-3 form-floating">
                                                    <select name="bu_transfer_assignment" id="bu_transfer_assignment" class="form-select form-control" required>
                                                        @foreach (config('constants.BU_LIST') as $val => $name) 
                                                            <option value="{{ $val }}">{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label  class="text-center" for="bu_transfer_assignment">Department</label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit" form="transferEmployeeForm">Submit
                                            <div id="transfer_reinstate_spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                                                <span class="sr-only"></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <button class="btn btn-warning ms-2" id="employee_reinstate">Reinstate
                            <div id="transfer_reinstate_spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                                <span class="sr-only"></span>
                            </div>
                        </button>
                    @endif
                @endif
                <form action="#" id="deact-react-form">
                    @csrf
                    <input hidden name="id" value="{{ $employee->id }}" type="text">
                </form>
            @endif
        </div>
    </div>
    @if ($detailOnly)
    @if($userInfo->id == $employee->id)
    <div class="modal fade" tabindex="-1" id="changePasswordModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-start">Change Account Password</h5>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div id="cp-success-msg">
                        </div>
                        <form action="#" id="changePasswordForm">
                            @csrf
                            <input type="text" hidden name="cp_id" value="{{ $employee->id }}">
                            <div class="row mb-3">
                                <div class="col-5 text-end">
                                    <label for="cp-current-pw" class="form-label">Enter Current Password</label>
                                </div>
                                <div class="col-7">
                                    <input name="cp_current_pw" type="password" class="form-control" id="cp-current-pw" required>
                                    <p id="current-pass-error"></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 text-end">
                                    <label for="cp-new-pw" class="form-label">Enter New Password</label>
                                    <small class="form-text text-secondary"><em>Minimum of 8 characters</em></small>
                                </div>
                                <div class="col-7">
                                    <input name="cp_new_pw" type="password" class="form-control" id="cp-new-pw" required>
                                    <p id="new-pass-error"></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 text-end">
                                    <label for="cp-confirm-pw" class="form-label">Confirm New Password</label>
                                </div>
                                <div class="col-7">
                                    <input name="cp_confirm_pw" type="password" class="form-control" id="cp-confirm-pw" required>
                                    <p id="confirm-pass-text"></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="cp-submit-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif



    <div class="pt-2">
        @if(!$detailOnly)
        <div class="row mb-2 ps-3 pe-3">
            <div class="col-6 g-3">
                <div class="row">
                    <h6 class="text-danger">※Requested by {{ $requestor }}</h6>
                </div>
            </div>
        </div>
        @endif
        <form action="{{ route('employees.regist') }}" method="POST">
            @csrf
            <div class="emp-regist-category p-3 mb-4 rounded-3">
                <h4 class="text-start">Employee </h4>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-md-4 g-3 form-floating">
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{ $employee->first_name }}" required disabled>
                        <label class="text-center" for="first_name">First Name</label>
                        @if ($errors->has('first_name'))
                        <p class="text-danger">{{ $errors->first('first_name') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-3 g-3 form-floating">
                        <input type="text" class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" value="{{ $employee->middle_name }}" disabled>
                        <label  class="text-center" for="middle_name">Middle Name</label>
                        @if ($errors->has('middle_name'))
                        <p class="text-danger">{{ $errors->first('middle_name') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-4 g-3 form-floating">
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="{{ $employee->last_name }}" required disabled>
                        <label  class="text-center" for="last_name">Last Name</label>
                        @if ($errors->has('last_name'))
                        <p class="text-danger">{{ $errors->first('last_name') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-1 g-3 form-floating">
                        <input type="text" class="form-control" name="name_suffix" id="name_suffix" placeholder="Suffix" value="{{ $employee->name_suffix }}" disabled>
                        <label  class="text-center small" for="name_suffix"><em>Suffix</em></label>
                        @if ($errors->has('name_suffix'))
                        <p class="text-danger">{{ $errors->first('name_suffix') }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-sm-5 g-3 form-floating">
                        <input type="date" class="form-control" name="birthdate" id="birthdate" placeholder="birthdate" value="{{ old('birthdate') ?: $employee->birthdate }}" pattern="\d{4}-\d{2}-\d{2}" required disabled>
                        <label  class="text-center" for="birthdate">Birth Date</label>
                        @if ($errors->has('birthdate'))
                        <p class="text-danger">{{ $errors->first('birthdate') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-sm-7 g-3 text-start">
                        <div class="d-flex align-items-center ps-1" style="height: 100%">
                            <div class="d-inline">
                                Gender:&nbsp&nbsp
                            </div>
                            <div class="d-inline">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="gender" id="femaleRadio" value="0" {{ $employee->gender == 0 ? "checked" : "" }} disabled>
                                    <label class="form-check-label" for="femaleRadio">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="gender" id="maleRadio" value="1" {{ $employee->gender == 1 ? "checked" : "" }} disabled>
                                    <label class="form-check-label" for="maleRadio">Male</label>
                                </div>
                            </div>
                            @if ($errors->has('gender'))
                            <p class="text-danger">{{ $errors->first('gender') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-md-6 col-12 g-3 form-floating">
                        <select name="position" id="position" class="form-select form-control" disabled>
                            @foreach (config('constants.POSITIONS') as $value => $name)
                                <option {{ old('position', $employee ? $employee->position : '') == $value ? "selected" : "" }} value="{{ $value 
                                     }}"> {{ $name }}</option>
                            @endforeach
                        </select>
                        <label  class="text-center" for="position">Position</label>
                        @if ($errors->has('position'))
                        <p class="text-danger">{{ $errors->first('position') }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-2 ps-3 pe-3">
                    @if ($userInfo->roles == config('constants.MANAGER_ROLE_VALUE') || $userInfo->id == $employee->id)
                    <div class="col-lg-2 col-4 g-3 ps-1">
                        <div class="d-flex align-items-center">
                            <div class="form-check ">
                                <label class="form-check-label" for="server-manage-flag">Manage Server</label>
                                <input type="checkBox" class="form-check-input" name="server_manage_flag" id="server-manage-flag" value="1" {{ $employee->server_manage_flag == 1 ? "checked" : "" }} disabled>
                            </div>
                            @if ($errors->has('server_manage_flag'))
                            <p class="text-danger">{{ $errors->first('server_manage_flag') }}</p>
                            @endif
                        </div>
                    </div>
                    @if (!in_array($employee->position, [config('constants.POSITION_MANAGER_VALUE'), config('constants.POSITION_ASSSITANT_MANAGER_VALUE')]))
                    <div class="col-lg-2 col-4 g-3 ps-1" id="admin-detail">
                        <div class="d-flex align-items-center" style="height: 100%">
                            <div class="form-check ">
                                <label class="form-check-label" for="is-admin-detail">Admin</label>
                                <input type="checkBox" class="form-check-input" name="is_admin" id="is-admin-detail" value="0" {{ $employee->roles == config('constants.ADMIN_ROLE_VALUE') ? "checked" : "" }} disabled>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            <div class="emp-regist-category mb-4 p-3 rounded-3">
                <h4 class="text-start">Contact Details</h4>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-md-4 g-3 form-floating">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" required value="{{ $employee->email }}" disabled>
                        <label for="email" class="text-center">Email Address</label>
                        @if ($errors->has('email'))
                        <p class="text-danger">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-md-4 g-3">
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <div class="form-floating">
                                <input type="text" class="form-control" name="cellphone_number" id="contact" placeholder="Contact Number" required value="{{ $employee->cellphone_number }}" disabled>
                                <label for="contact" class="text-center">Contact Number</label>
                            </div>
                        </div>
                        @if ($errors->has('cellphone_number'))
                        <p class="text-danger">{{ $errors->first('cellphone_number') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-md-4  g-3 form-floating">
                        <input type="text" class="form-control" name="other_contact_info" id="other_contact" placeholder="Other Contact Number" value="{{ $employee->other_contact_info }}" disabled>
                        <label for="other_contact" class="text-center">Other Contact Info (optional)</label>
                        @if ($errors->has('other_contact_info'))
                        <p class="text-danger">{{ $errors->first('other_contact_info') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Passport Details --}}
            <div class="emp-regist-category mb-4 p-3 rounded-3">
                <h4 class="text-start">Passport Details</h4>

                <div class="row row-list">
                    <div class="col-lg-1 col-12 text-start" id="status-label">
                        Status: 
                    </div>
                    <div class="col-lg-11 col-12" style="text-align: left">

                        @foreach (config('constants.PASSPORT_STATUS_LIST') as $name => $details)
                             <input class="passport_status btn-check" type="radio" name="passport_status" did="status-{{$name}}" value="{{$details['val']}}" {{ old('passport_status', $employee ? $employee->passport_status : $details['val'] ) ==  $details['val']  ? "checked" : "" }} disabled />
                                <label class="form-check-label passport-status btn btn-outline-primary" for="status-{{$name}}">
                                    {{  $details['name'] }}
                                </label>
                        @endforeach
                        
                    </div>
                </div>

                {{-- With Valid Passport section --}}
                <div id="withPassport" class="">
                    <div class="row mb-2 ps-3 pe-3">
                        <div class="col-12 col-md-4 g-3 form-floating">
                            <input type="text" class="form-control" name="passport_number" id="passport_number" placeholder="Passport Number" required value="{{ $employee->passport_number }}" disabled>
                            <label for="passport_number" class="text-center">Passport Number</label>
                            @if ($errors->has('passport_number'))
                            <p class="text-danger">{{ $errors->first('passport_number') }}</p>
                            @endif
                        </div>
                        <div class="col-12 col-md-4 g-3 form-floating">
                            <select name="passport_type" id="passport_type" class="form-select form-control" disabled>
                                <option value="">Select passport type</option>
                                <option {{ old('passport_type', $employee ? $employee->passport_type : '') == config('constants.PASSPORT_TYPE_ORDINARY_VALUE')   ? "selected" : "" }} value="{{ config('constants.PASSPORT_TYPE_ORDINARY_VALUE')    }}">{{ config('constants.PASSPORT_TYPE_1_NAME') }}</option>
                                <option {{ old('passport_type', $employee ? $employee->passport_type : '') == config('constants.PASSPORT_TYPE_OFFICIAL_VALUE')   ? "selected" : "" }} value="{{ config('constants.PASSPORT_TYPE_OFFICIAL_VALUE')    }}">{{ config('constants.PASSPORT_TYPE_2_NAME') }}</option>
                                <option {{ old('passport_type', $employee ? $employee->passport_type : '') == config('constants.PASSPORT_TYPE_DIPLOMATIC_VALUE') ? "selected" : "" }} value="{{ config('constants.PASSPORT_TYPE_DIPLOMATIC_VALUE')  }}">{{ config('constants.PASSPORT_TYPE_3_NAME') }}</option>
                            </select>
                            <!-- <input type="text" class="form-control" name="passport_type" id="passport_type" placeholder="Passport Type" required value="{{ $employee->passport_type }}" disabled> -->
                            <label for="passport_type" class="text-center">Passport Type</label>
                            @if ($errors->has('passport_type'))
                            <p class="text-danger">{{ $errors->first('passport_type') }}</p>
                            @endif
                        </div>
                        <div class="col-12 col-md-4 g-3 form-floating">
                            <input type="text" class="form-control" name="issuing_authority" id="issuing_authority" placeholder="Issuing Authority" required value="{{ $employee->issuing_authority }}" disabled>
                            <label for="issuing_authority" class="text-center">Issuing Authority</label>
                            @if ($errors->has('issuing_authority'))
                            <p class="text-danger">{{ $errors->first('issuing_authority') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2 ps-3 pe-3">
                        <div class="col-6 col-md-4 g-3 form-floating">
                            <input type="date" class="form-control" name="date_of_issue" id="date_of_issue" placeholder="Date of issue" value="{{ old('date_of_issue') ?: $employee->date_of_issue }}" pattern="\d{4}-\d{2}-\d{2}" required disabled>
                            <label  class="text-center" for="date_of_issue">Date of Issue</label>
                            @if ($errors->has('date_of_issue'))
                            <p class="text-danger">{{ $errors->first('date_of_issue') }}</p>
                            @endif
                        </div>
                        <div class="col-6 col-md-4 g-3 form-floating">
                            <input type="date" class="form-control" name="passport_expiration_date" id="passport_expiration_date" placeholder="Valid until" value="{{ old('passport_expiration_date') ?: $employee->passport_expiration_date }}" pattern="\d{4}-\d{2}-\d{2}" required disabled>
                            <label  class="text-center" for="passport_expiration_date">Valid Until</label>
                            @if ($errors->has('passport_expiration_date'))
                            <p class="text-danger">{{ $errors->first('passport_expiration_date') }}</p>
                            @endif
                        </div>
                        <div class="col-12 col-md-4 g-3 form-floating">
                            <input type="text" class="form-control" name="place_of_issue" id="place_of_issue" placeholder="Place of Issue" required value="{{ $employee->place_of_issue }}" disabled>
                            <label for="place_of_issue" class="text-center">Place of Issue</label>
                            @if ($errors->has('place_of_issue'))
                            <p class="text-danger">{{ $errors->first('place_of_issue') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- With Passport Appointment section --}}
                <div id="withAppointment" class="">
                    <div class="row mb-2 ps-3 pe-3">
                        <div class="col-6 g-3 form-floating">
                            <input type="date" class="form-control" name="date_of_appointment" id="date_of_appointment" placeholder="Date of Appointment" value="{{ old('date_of_appointment') ?: $employee->date_of_appointment }}" pattern="\d{4}-\d{2}-\d{2}" required disabled>
                            <label  class="text-center" for="date_of_appointment">Date of Appointment: Passport Application</label>
                            @if ($errors->has('date_of_appointment'))
                            <p class="text-danger">{{ $errors->first('date_of_appointment') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Without Passport Appointment section --}}
                <div id="withoutAppointment" class="d-none" style="text-align: left">
                    <div class="row mb-2 ps-5 pe-3">
                        <div class="col-lg-9 g-3">
                            <h5>Reason for No Appointment</h5>
                            <textarea class="form-control" name="no_appointment_reason"  rows="3" id="no_appointment_reason" placeholder="Please state the reason for not having a passport appointment" required disabled>{{ old('no_appointment_reason') ?: $employee->no_appointment_reason }}</textarea>
                        </div>
                        @if ($errors->has('no_appointment_reason'))
                        <p class="text-danger text-start">{{ $errors->first('no_appointment_reason') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Waiting for Delivery section --}}
                <div id="waitingDelivery" class="d-none" style="text-align: left">
                    <div class="row mb-2 ps-3 pe-3">
                        <div class="col-6 g-3 form-floating">
                            <input type="date" class="form-control" name="date_of_delivery" id="date_of_delivery" placeholder="Expected Date of Delivery" value="{{ old('date_of_delivery') ?: $employee->date_of_delivery }}" pattern="\d{4}-\d{2}-\d{2}" required disabled>
                            <label  class="text-center" for="date_of_delivery">Expected Date of Delivery</label>
                            @if ($errors->has('date_of_delivery'))
                            <p class="text-danger">{{ $errors->first('date_of_delivery') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permanent Address --}}
            <div class="emp-regist-category mb-4 p-3 rounded-3">
                <h4 class="text-start">Permanent Address</h4>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 g-3 form-floating">
                        <input type="text" class="form-control" name="permanent_address_street" id="perm-add-strt" placeholder="Street" required value="{{ $employee->permanent_address_street }}" disabled>
                        <label for="perm-add-strt" class="text-center">Street</label>
                        @if ($errors->has('permanent_address_street'))
                        <p class="text-danger">{{ $errors->first('permanent_address_street') }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="permanent_address_city" id="perm-add-town" placeholder="Town" required value="{{ $employee->permanent_address_city }}" disabled>
                        <label for="perm-add-town" class="text-center">Town/City</label>
                        @if ($errors->has('permanent_address_city'))
                        <p class="text-danger">{{ $errors->first('permanent_address_city') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="permanent_address_province" id="perm-add-prov" placeholder="Province" required value="{{ $employee->permanent_address_province }}" disabled>
                        <label for="perm-add-prov" class="text-center">Province/Region</label>
                        @if ($errors->has('permanent_address_province'))
                        <p class="text-danger">{{ $errors->first('permanent_address_province') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="permanent_address_postalcode" id="perm-add-postal" placeholder="Postal Code" required value="{{ $employee->permanent_address_postalcode }}" disabled>
                        <label for="perm-add-postal" class="text-center">Postal Code</label>
                        @if ($errors->has('permanent_address_postalcode'))
                        <p class="text-danger">{{ $errors->first('permanent_address_postalcode') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Current Address --}}
            <div class="emp-regist-category mb-4 p-3 rounded-3">
                <h4 class="text-start">Current Address</h4>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 g-3 form-floating">
                        <input type="text" class="form-control" name="current_address_street" id="cur-add-strt" placeholder="Street" required value="{{ $employee->current_address_street }}" disabled>
                        <label for="cur-add-strt" class="text-center">Street</label>
                        @if ($errors->has('current_address_street'))
                        <p class="text-danger">{{ $errors->first('current_address_street') }}</p>
                        @endif
                    </div>
                </div>
                <div class="row mb-2 ps-3 pe-3">
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="current_address_city" id="cur-add-town" placeholder="Town" required value="{{ $employee->current_address_city }}" disabled>
                        <label for="cur-add-town" class="text-center">Town/City</label>
                        @if ($errors->has('current_address_city'))
                        <p class="text-danger">{{ $errors->first('current_address_city') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="current_address_province" id="cur-add-prov" placeholder="Province" required value="{{ $employee->current_address_province }}" disabled>
                        <label for="cur-add-prov" class="text-center">Province/Region</label>
                        @if ($errors->has('current_address_province'))
                        <p class="text-danger">{{ $errors->first('current_address_province') }}</p>
                        @endif
                    </div>
                    <div class="col-12 col-lg-4 g-3 form-floating">
                        <input type="text" class="form-control" name="current_address_postalcode" id="cur-add-postal" placeholder="Postal Code" required value="{{ $employee->current_address_postalcode }}" disabled>
                        <label for="cur-add-postal" class="text-center">Postal Code</label>
                        @if ($errors->has('current_address_postalcode'))
                        <p class="text-danger">{{ $errors->first('current_address_postalcode') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if ($detailOnly)
    <div class="emp-regist-category mb-4 p-3 rounded-3 table-avoid-overflow">
        <div class="d-flex justify-content-between">
            <h4 class="text-start">Projects</h4>
            <button class="btn btn-primary" data-bs-target="#linkProjectModal" data-bs-toggle="modal">Add</button>
        </div>
        <table class="table table-bordered border-secondary mt-3" id="project-tbl">
            <thead class="bg-primary text-white fw-bold">
                <tr>
                    <th style="width:50%">NAME</th>
                    <th style="width:30%">DATE</th>
                    <th style="">STATUS</th>
                </tr>
            </thead>
            <tbody class="">
                @if(!empty($empProject))
                    @foreach ($empProject as $project)
                        <tr>
                            <td><a href="{{ route('projects.details', ['id' => $project['project_id']]) }}" class="text-decoration-none">{{ $project['name'] }}</a></td>
                            <td>{{ date("Y/m/d", strtotime($project['start_date']))  }} - {{ $project['end_date'] ? date("Y/m/d", strtotime($project['end_date'])) : '' }}</td>
                            <td>{{ $project['project_status'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="emp-regist-category mb-4 p-3 rounded-3 table-avoid-overflow">
        <div class="d-flex justify-content-between">
            <h4 class="text-start">Laptops</h4>
            @if ($userInfo->id == $employee->id || in_array($userInfo->roles, [config('constants.ADMIN_ROLE_VALUE'), config('constants.MANAGER_ROLE_VALUE')]))
                <button class="btn btn-primary" data-bs-target="#linkLaptopModal" data-bs-toggle="modal">Add</button>
            @endif
        </div>
        <table class="table table-bordered border-secondary mt-3" id="laptop-tbl">
            <thead class="bg-primary text-white fw-bold">
                <tr>
                    <th>TAG NUMBER</th>
                    <th>OFFICE PC BROUGHT HOME</th>
                    <th>LAPTOP MAKE</th>
                    <th>LAPTOP MODEL</th>
                    <th>VPN ACCESS</th>
                    <th style="width:30%">REMARKS</th>
                </tr>
            </thead>
            <tbody class="">
                @if (!empty($empLaptop))
                    @foreach ($empLaptop as $laptop)
                    <tr>
                        <td><a href="{{ route('laptops.details', ['id' => $laptop['id']]) }}" class="text-decoration-none">{{ $laptop['tag_number'] }}</a></td>
                        <td>{{ $laptop['brought_home'] }}</td>
                        <td>{{ $laptop['laptop_make'] }}</td>
                        <td>{{ $laptop['laptop_model'] }}</td>
                        <td>{{ $laptop['use_vpn'] }}</td>
                        <td>{{ $laptop['remarks'] }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @endif


    @if (!$detailOnly)
    <div class="text-center p-4">
        <button class="btn btn-danger btn-lg mb-5 ms-4 rqst-btn"  data-bs-target="#rejectRequestModal" data-bs-toggle="modal" id="reject-request" style="width: 150px">
            Reject  <div id="employee_reject_spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                <span class="sr-only"></span>
            </div>
        </button>
        <button class="btn btn-success btn-lg mb-5 ms-4 rqst-btn" id="approve-request"  form="approve-request-form" style="width: 150px">
            Approve <div id="employee_approve_spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                <span class="sr-only"></span>
            </div>
        </button>
        <form action="{{ route('employees.store') }}" method="POST" id="approve-request-form">
            @csrf
            <input type="text" name="id" hidden value="{{ $employee->id }}">
        </form>
    </div>
    <div class="modal fade" tabindex="-1" id="rejectRequestModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="p-2">
                        <form action="{{ route('employees.reject') }}" method="POST" id="reject-request-form">
                            @csrf
                            <input type="text" name="id" value="{{ $employee->id }}" hidden>
                            <div class="mb-2">
                                <textarea class="form-control" name="reason" placeholder="Reason" rows="5" id="reject-reason" required></textarea>
                            </div>
                            <p id="reject-reason-error"></p>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger" id="reject-sub" type="submit" form="reject-request-form">
                        Reject <div id="employee_reject_submit_spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                            <span class="sr-only"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($detailOnly)
    <div class="modal fade" tabindex="-1" id="linkProjectModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-start">Link Project</h5>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div id="lp-success-msg"></div>
                        <form action="#" id="linkProjectForm">
                            @csrf
                            <input type="text" hidden name="lp_employee_id" value="{{ $employee->id }}">
                            <div class="row mb-2">
                                <div class="col-12 g-3 form-floating">
                                    <select name="project_id" class="form-select" id="projectList" required>
                                        @foreach ( $projectList as $project )
                                            <option data-mindate="{{ date('Y-m-d', strtotime($project['start_date']))  }}" data-maxdate="{{ !empty($project['end_date']) ? date('Y-m-d', strtotime($project['end_date'])) : ""  }}" value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="projectList" class="text-center">Project Name</label>
                                    <span id="error-lp-proj-name"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 g-3 form-floating">
                                    <input type="date" name="project_start_date" class="form-control" id="project-start" required>
                                    <label for="project-start" class="text-center">Start Date</label>
                                    <span id="error-lp-proj-start"></span>
                                </div>
                                <div class="col-6 g-3 form-floating">
                                    <input type="date" name="project_end_date" class="form-control" id="project-end">
                                    <label for="project-end" class="text-center">End Date</label>
                                    <span id="error-lp-proj-end"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 g-3 form-floating">
                                    <select name="project_role" id="projectRoleList" class="form-select" required>
                                        @foreach (config('constants.PROJECT_ROLES') as $val => $text )
                                        <option value="{{ $val }}">{{ $text }}</option>
                                        @endforeach
                                    </select>
                                    <label for="projectRoleList" class="text-center">Role</label>
                                    <span id="error-lp-proj-role"></span>
                                </div>
                                <div class="col-6 g-3 text-start">
                                    <p></p>
                                    <div class="form-check">
                                        <label for="project_onsite" class="form-check-label user-select-none">Onsite</label>
                                        <input type="checkbox" class="form-check-input" name="project_onsite" id="project_onsite" value="1">
                                    </div>  
                                </div>
                            </div>
                            <div class="row pt-2">
                                <h6 class="text-start">Remarks</h6>
                            </div>
                            <div class="row text-start">
                                <div class="gs-3 ge-3 gt-1">
                                    <textarea name="remarks" id="link_remarks" rows="3" class="form-control"></textarea>
                                    <span id="error-lp-remarks"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="lp-submit-btn">Link</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="linkLaptopModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-start">Link Laptop</h5>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div id="ll-success-msg"></div>
                        <form action="#" id="linkLaptopForm">
                            @csrf
                            <input type="text" hidden name="ll_employee_id" value="{{ $employee->id }}">
                            <div class="row mb-2">
                                <div class="col-6 g-3 form-floating">
                                    <select name="laptop_id" id="laptopList" class="form-select" required>
                                        @foreach ($laptopList as $laptop)
                                            <option value="{{ $laptop['id'] }}">{{ $laptop['tag_number'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="laptopList" class="text-center">Tag Number</label>
                                    <span id="error-laptop-id"></span>
                                </div>
                            </div>
                            <div class="row mb-4 text-start">
                                <div class="col-6 g-3">
                                    <div class="form-check">
                                        <label for="ll-brought-home" class="form-check-label">Brought Home?</label>
                                        <input type="checkbox" class="form-check-input" name="laptop_brought_home" id="ll-brought-home" value="1">
                                    </div>  
                                </div>
                                <div class="col-6 g-3">
                                    <div class="form-check">
                                        <label for="ll-vpn" class="form-check-label">VPN Access?</label>
                                        <input type="checkbox" class="form-check-input" name="laptop_vpn" id="ll-vpn" value="1">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 pt-2 text-start">
                                <h6>Remarks</h6>
                            </div>
                            <div class="row text-start">
                                <div class="gs-3 ge-3 gt-1">
                                    <textarea name="remarks" id="ll-remarks" rows="3" class="form-control"></textarea>
                                    <span id="error-ll-remarks"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="ll-submit-btn">Link
                        <div id="ll-link-spinner" class="spinner-border text-light spinner-border-sm" role="status" style="display: none">
                            <span class="sr-only"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
	<button id="btnTop" title="Go to top"><i class="bi bi-arrow-up"></i></button> 
</div>

@include('footer')