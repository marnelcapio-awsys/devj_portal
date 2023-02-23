@include('header')
@include('headerMenu')
<script src="{{ asset('js/servers_dum.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/server.css') }}">
<div class="container ps-md-3 pe-md-3 pt-5">
    <h3 class="fw-bold">Server Registration</h3>
    <div class="pt-4">
        <form id="server_reg_form" action="{{ route('servers.regist') }}" method="POST">
            @csrf
            {{-- <input type="text" name="id" value="{{ !empty($serverData->id) ? $serverData->id : '' }}" hidden > --}}
            <div class="group-category p-3 mb-4 rounded-3">
                {{-- @if ($errors->has('id'))
                <p class="text-danger">{{ $errors->first('id') }}</p>
                @endif --}}
                <div class="row mb-2 ps-5 pe-3">
                    <div class="col-lg-3 col-6 g-3 form-floating">
                       <input type="text" name="server_name" class="form-control" id="server_name" placeholder="Tag Number" value="{{ old('server_name', !empty($serverData) ? $serverData->server_name : '') }}" required>
                       <label for="server_name" >Server Name</label>
                       @if ($errors->has('server_name'))
                       <p class="text-danger">{{ $errors->first('server_name') }}</p>
                       @endif
                    </div>
                    <div class="col-6 g-3">
                        <div class="d-flex align-items-center" style="height: 100%">
                            <div class="form-check ">
                                <label class="form-check-label" for="server_status">Active Status</label>
                                <input type="checkBox" class="form-check-input" name="status" id="server_status" value="1" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 ps-5 pe-3">
                    <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="server_ip" class="form-control" id="server_ip" placeholder="IP Address" value="{{ old('server_ip', !empty($serverData) ? $serverData->server_ip : '') }}" required>
                        <label for="server_ip" >IP Address</label>
                        @if ($errors->has('server_ip'))
                        <p class="text-danger">{{ $errors->first('server_ip') }}</p>
                        @endif
                     </div>
                     <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="os" class="form-control" id="os" placeholder="Operating System" value="{{ old('os', !empty($serverData) ? $serverData->os : '') }}" required>
                        <label for="os" >Operating System</label>
                        @if ($errors->has('os'))
                        <p class="text-danger">{{ $errors->first('os') }}</p>
                        @endif
                     </div>
                     <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="motherboard" class="form-control" id="motherboard" placeholder="Motherboard" value="{{ old('motherboard', !empty($serverData) ? $serverData->motherboard : '') }}" required>
                        <label for="motherboard" >Motherboard</label>
                        @if ($errors->has('motherboard'))
                        <p class="text-danger">{{ $errors->first('motherboard') }}</p>
                        @endif
                     </div>
                </div>
                <div class="row mb-2 ps-5 pe-3">
                    <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="cpu" class="form-control" id="cpu" placeholder="CPU" value="{{ old('cpu', !empty($serverData) ? $serverData->cpu : '') }}" required>
                        <label for="cpu" >CPU</label>
                        @if ($errors->has('cpu'))
                        <p class="text-danger">{{ $errors->first('cpu') }}</p>
                        @endif
                    </div>
                    <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="memory" class="form-control" id="memory" placeholder="Memory" value="{{ old('memory', !empty($serverData) ? $serverData->memory : '') }}" required>
                        <label for="memory" >Memory</label>
                        @if ($errors->has('memory'))
                        <p class="text-danger">{{ $errors->first('memory') }}</p>
                        @endif
                    </div>
                    <div class="col-lg-3 col-4 g-3 form-floating">
                        <input type="text" name="hdd" class="form-control" id="hdd" placeholder="HDD" value="{{ old('hdd', !empty($serverData) ? $serverData->hdd : '') }}" required>
                        <label for="hdd" >HDD</label>
                        @if ($errors->has('hdd'))
                        <p class="text-danger">{{ $errors->first('hdd') }}</p>
                        @endif
                    </div>
                </div>
                <div class="row pt-4 ps-3 pe-3">
                    <h5>Function/Role</h5>
                </div>
                <div class="row mb-2 ps-5 pe-3">
                    <div class="col-lg-9 g-3">
                        <textarea class="form-control" name="function_role"  rows="3" id="function_role">{{ old('remarks', !empty($serverData) ? $serverData->remarks : '') }}</textarea>
                    </div>
                    @if ($errors->has('remarks'))
                    <p class="text-danger">{{ $errors->first('remarks') }}</p>
                    @endif
                </div>
                <div class="row pt-4 ps-3 pe-3">
                    <h5>Remarks</h5>
                </div>
                <div class="row mb-2 ps-5 pe-3">
                    <div class="col-lg-9 g-3">
                        <textarea class="form-control" name="remarks"  rows="3" id="remarks">{{ old('remarks', !empty($serverData) ? $serverData->remarks : '') }}</textarea>
                    </div>
                    @if ($errors->has('remarks'))
                    <p class="text-danger">{{ $errors->first('remarks') }}</p>
                    @endif
                </div>
            </div>

            <h3 class="fw-bold d-inline-block">Utilization</h3>&nbsp;&nbsp;&nbsp;
            <span id="legend_text">
                <span id="status_legend">Status Legend:</span>&nbsp;&nbsp;&nbsp;
                <span class="status_normal">Normal: 0%-60%</span>,&nbsp;
                <span class="status_stable">Stable: 61%-89%</span>,&nbsp;
                <span class="status_critical">Critical: 90%-100%</span>
            </span>

            <section id="usage">
{{-- ==================================== HDD Usage ==================================== --}}      
                <div class="group-category p-3 mb-4 mt-2 pt-3 rounded-3">
                    <div class="row">
                        <div class="col">
                            <h4 class="subheader">HDD</h4>&nbsp;&nbsp;  
                            <div class="status_group fs-6 fw-bold">
                                Status: <span id="hdd_status">Normal</span>
                                <input type="text" hidden name="hdd_status">
                            </div>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-primary text-end" id="add_partition">Add</button>
                        </div>
                    </div>

                    <div class="row g-3 mb-2 pt-3" id="hdd_partitions">

                        {{-- partition start --}}
                        <div class="partition_section col-md-6" >
                            <div class="hdd_partition p-1 pt-2">
                                <div class="row p-2">
                                    <div class="col-md-8 col-9">
                                        <div class="row">
                                            <label for="partition_1" class="col-auto fs-5 fw-bold align-baseline radio">Partition</label>
                                            <div class="col-auto">
                                            <input name="hdd[1][partition_name]" type="text" class="form-control" id="partition_1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-3 text-end">
                                        <button class="btn btn-danger remove_partition">Remove</button>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <div class="row g-0 pt-1">
                                        <div class="col-3 form-floating">
                                            <input name="hdd[1][total]" type="text" class="form-control" id="hdd_total_1" placeholder="total">
                                            <label for="hdd_total_1">Total</label>
                                        </div>
                                        <div class="col-2 form-floating">
                                            <select name="hdd[1][total_unit]" id="hdd_total_unit_1" class="form-select form-control">
                                                @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                                    <option value="{{ $idx }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                            <label for="hdd_total_unit_1">Unit</label>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-1 fw-semibold">
                                        Select mode of input:
                                    </div>
                                    <div class="row g-1 pt-2">
                                        <div class="col">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input hdd_select_radio" name="hdd[1][input_type]" id="hdd_size_radio_1" value="1" checked>
                                                <label class="form-check-label text-start" for="hdd_size_radio_1">Size</label>
                                            </div>
                                            <div class="row g-0 pt-2 text-center">
                                                <div class="col-6 form-floating">
                                                    <input name="hdd[1][used]" type="text" class="form-control" id="hdd_used_1" placeholder="used">
                                                    <label for="hdd_used_1">Used</label>
                                                </div>
                                                <div class="col-4 form-floating">
                                                    <select name="hdd[1][used_unit]" id="hdd_used_unit_1" class="form-select form-control">
                                                        @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                                            <option value="{{ $idx }}">{{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="hdd_used_unit_1">Unit</label>
                                                </div>
                                            </div>
                                            <div class="row g-0 pt-2 text-center">
                                                <div class="col-6 form-floating">
                                                    <input name="hdd[1][free]" type="text" class="form-control" id="hdd_free_1" placeholder="Free">
                                                    <label for="hdd_free_1">Free</label>
                                                </div>
                                                <div class="col-4 form-floating">
                                                    <select name="hdd[1][free_unit]" id="hdd_free_unit_1" class="form-select form-control">
                                                        @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                                            <option value="{{ $idx }}">{{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="hdd_free_unit_1">Unit</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" class="form-check-input hdd_select_radio" name="hdd[1][input_type]" id="hdd_percentage_radio_1" value="2">
                                                <label class="form-check-label text-start" for="hdd_percentage_radio_1">Percentage</label>
                                            </div>
                                            <div class="row g-0 pt-2 text-center">
                                                <div class="col-6 form-floating">
                                                    <input name="hdd[1][used_percentage]" type="text" class="form-control" id="hdd_used_percentage_1" placeholder="used" disabled>
                                                    <label for="hdd_used_percentage_1">Used</label>
                                                </div>
                                            </div>
                                            <div class="row g-0 pt-2 text-center">
                                                <div class="col-6 form-floating">
                                                    <input name="hdd[1][free_percentage]" type="text" class="form-control" id="hdd_free_percentage_1" placeholder="Free" disabled>
                                                    <label for="hdd_free_percentage_1">Free</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- partition end --}}
                    </div>
                </div>

{{-- ==================================== Memory Usage and CPU Usage ==================================== --}}

                <div class="row" id="memory_usage_section">
                    <div class="col-md-6 ps-2">
                        <div class="group-category p-3 mb-4 pt-3 rounded-3">
                            <h4 class="subheader">Memory</h4>&nbsp;&nbsp;  
                            <div class="status_group fs-6 fw-bold">
                                Status: <span id="memory_status"></span>
                                <input type="text" hidden name="memory_status">
                            </div>
                            <div class="row g-0 pt-3">
                                <div class="col-3 form-floating">
                                    <input name="memory_total" type="text" class="form-control" id="memory_total" placeholder="total">
                                    <label for="memory_total">Total</label>
                                </div>
                                <div class="col-2 form-floating">
                                    <select name="memory_total_unit" id="memory_total_unit" class="form-select form-control">
                                        @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                            <option value="{{ $idx }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    <label for="memory_total_unit">Unit</label>
                                </div>
                            </div>
                            <div class="mt-2 pt-1 fw-semibold">
                                Select mode of input:
                            </div>
                            <div class="row g-1 pt-2" id="memory_size_section">
                                <div class="col">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="memory_input_type" id="memory_size_radio" value="1" {{ old('memory_input_type', 1) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label text-start" for="memory_size_radio">Size</label>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-6 form-floating">
                                            <input name="memory_used" type="text" class="form-control" id="memory_used" placeholder="used" value="{{ old('memory_used', !empty($serverData) ? $serverData->memory_used_size : '') }}">
                                            <label for="memory_used">Used</label>
                                        </div>
                                        <div class="col-4 form-floating">
                                            <select name="memory_used_unit" id="memory_used_unit" class="form-select form-control">
                                                @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                                    <option value="{{ $idx }}" {{ !empty($serverData) && $serverData->memory_used_size_type == $idx ? 'selected' : '' }}>{{ $val }}</option>
                                                @endforeach
                                            </select>
                                            <label for="memory_used_unit">Unit</label>
                                        </div>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-6 form-floating">
                                            <input name="memory_free" type="text" class="form-control" id="memory_free" placeholder="Free" value="{{ old('memory_free', !empty($serverData) ? $serverData->memory_free_size : '') }}">
                                            <label for="memory_free">Free</label>
                                        </div>
                                        <div class="col-4 form-floating">
                                            <select name="memory_free_unit" id="memory_free_unit" class="form-select form-control">
                                                @foreach (config('constants.SIZE_UNITS') as $idx => $val)
                                                    <option value="{{ $idx }}" {{ !empty($serverData) && $serverData->memory_free_size_type == $idx ? 'selected' : '' }}>{{ $val }}</option>
                                                @endforeach
                                            </select>
                                            <label for="memory_free_unit">Unit</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" id="memory_percentage_section">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="memory_input_type" id="memory_percentage_radio" value="2" {{ old('memory_input_type') == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label text-start" for="memory_percentage_radio">Percentage</label>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-6 form-floating">
                                            <input name="memory_used_percentage" type="text" class="form-control" id="memory_used_percentage" placeholder="used" disabled value="{{ old('memory_used_percentage', !empty($serverData) ? $serverData->memory_used_percentage : '') }}">
                                            <label for="memory_used_percentage">Used</label>
                                        </div>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-6 form-floating">
                                            <input name="memory_free_percentage" type="text" class="form-control" id="memory_free_percentage" placeholder="Free" disabled value="{{ old('memory_free_percentage', !empty($serverData) ? $serverData->memory_free_percentage : '') }}">
                                            <label for="memory_free_percentage">Free</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ps-2" id="cpu_usage_section">
                        <div class="group-category p-3 mb-4 pt-3 rounded-3">
                            <h4 class="subheader">CPU (%)</h4>&nbsp;&nbsp;  
                            <div class="status_group fs-6 fw-bold">
                                Status: <span id="cpu_status"></span>
                                <input type="text" hidden name="cpu_status">
                            </div>
                            <div class="mt-2 pt-1 fw-semibold">
                                Select the Operating System:
                            </div>
                            <div class="row g-1 pt-3">
                                <div class="col-lg-4 col-md-6 col-4" id="linux_usage">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="os_type" id="linux_radio" value="1" {{ old('os_type', 1) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label text-start" for="linux_radio">Linux</label>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-lg-8 col-md-6 col-8 form-floating">
                                            <input name="us" type="text" class="form-control" id="us" placeholder="us" value="{{ old('us', !empty($serverData) ? $serverData->linux_us_percentage : '') }}">
                                            <label for="us">us</label>
                                        </div>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-lg-8 col-md-6 col-8 form-floating">
                                            <input name="ny" type="text" class="form-control" id="ny" placeholder="ny" value="{{ old('ny', !empty($serverData) ? $serverData->linux_ny_percentage : '') }}">
                                            <label for="ny">ny</label>
                                        </div>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-lg-8 col-md-6 col-8 form-floating">
                                            <input name="sy" type="text" class="form-control" id="sy" placeholder="sy" value="{{ old('sy', !empty($serverData) ? $serverData->linux_sy_percentage : '') }}">
                                            <label for="sy">sy</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-4">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" name="os_type" id="other_os_radio" value="2" {{ old('os_type') == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label text-start" for="other_os_radio">Windows, etc</label>
                                    </div>
                                    <div class="row g-0 pt-2 text-center">
                                        <div class="col-lg-8 col-md-6 col-8 form-floating">
                                            <input name="other_os_percentage" type="text" class="form-control" disabled value="{{ old('other_os_percentage', !empty($serverData) ? $serverData->other_os_percentage : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="text-center p-3">
                <button class="btn btn-primary btn-lg mb-5" id="server-reg-submit" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

@include('footer')

