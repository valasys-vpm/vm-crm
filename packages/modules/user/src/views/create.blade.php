@extends('layouts.master')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">User Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('user') }}">User Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Create New User</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ form-element ] start -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            @include('layouts.alert')
                                            <h5>Create New User</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-user-create" method="post" action="{{ route('user.store') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="first_name" name="first_name" placeholder="Enter first name">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="last_name" name="last_name" placeholder="Enter last name">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="email">Email<span class="text-danger">*</span></label>
                                                                <input type="email" class="form-control btn-square" id="email" name="email" placeholder="Enter email">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="role_id">Role<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square" id="role_id" name="role_id">
                                                                    @foreach($resultRoles as $role)
                                                                        <option value="{{$role->id}}">{{ $role->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="emp_code">Employee Code<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square text-uppercase" id="emp_code" name="emp_code" placeholder="Enter employee code">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="department">Department<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="department" name="department" placeholder="Enter department">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="designation">Designation<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="designation" name="designation" placeholder="Enter designation">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="reporting_manager_id">Reporting Manager</label>
                                                                <select class="form-control btn-square" id="reporting_manager_id" name="reporting_manager_id">
                                                                    <option value="">-- Select Reporting Manager --</option>
                                                                    @foreach($resultUsers as $user)
                                                                        <option value="{{$user->id}}">{{ $user->userDetail->first_name.' '.$user->userDetail->last_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="status">Status</label>
                                                                <select class="form-control btn-square" id="status" name="status">
                                                                    @foreach(\Modules\User\enum\UserStatus::USER_STATUS as $user_status => $value)
                                                                        <option value="{{ $user_status }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-square">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ form-element ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script>
        $(function(){
            $("#form-user-create").validate({
                focusInvalid: false,
                rules: {
                    'first_name' : { required : true },
                    'last_name' : { required : true },
                    'email' : {
                        required : true,
                        email : true,
                        remote : {
                            url : '{{ route('user.validate.email') }}',
                            data : {
                                email : function(){
                                    return $("#email").val();
                                }
                            }
                        }
                    },
                    'emp_code' : {
                        required : true,
                        remote : {
                            url : '{{ route('user.validate.emp_code') }}',
                            data : {
                                emp_code : function(){
                                    return $("#emp_code").val();
                                }
                            }
                        }
                    },
                    'department' : { required : true },
                    'designation' : { required : true },
                },
                messages: {
                    'first_name' : { required: "Please enter first name" },
                    'last_name' : { required: "Please enter last name" },
                    'email' : {
                        required: "Please enter email",
                        remote: "Email already exists"
                    },
                    'emp_code' : {
                        required: "Please enter employee code",
                        remote: "Employee code already exists"
                    },
                    'department' : { required: "Please enter department name" },
                    'designation' : { required: "Please enter designation name" }
                },
                errorPlacement: function errorPlacement(error, element) {
                    var $parent = $(element).parents('.form-group');

                    // Do not duplicate errors
                    if ($parent.find('.jquery-validation-error').length) {
                        return;
                    }

                    $parent.append(
                        error.addClass('jquery-validation-error small form-text invalid-feedback')
                    );
                },
                highlight: function(element) {
                    var $el = $(element);
                    var $parent = $el.parents('.form-group');

                    $el.addClass('is-invalid');

                    // Select2 and Tagsinput
                    if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                        $el.parent().addClass('is-invalid');
                    }
                },
                unhighlight: function(element) {
                    $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
                }
            });
        });
    </script>
@append
