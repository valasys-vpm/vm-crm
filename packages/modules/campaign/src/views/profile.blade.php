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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">User Details</a></li>
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
                                <!-- [ task-detail ] start -->
                                <div class="col-xl-4 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col-auto">
                                                    <img class="img-fluid rounded-circle" style="width:80px;" src="{{asset('public/template')}}/assets/images/user/avatar-2.jpg" alt="dashboard-user">
                                                </div>
                                                <div class="col">
                                                    <h5>{{ $resultUser->userDetail->first_name.' '.$resultUser->userDetail->last_name }}</h5>
                                                    <span>{{ $resultUser->userDetail->designation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>User Details</h5>
                                        </div>
                                        <div class="card-block task-details">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Email:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->email }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Employee Code:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->userDetail->emp_code }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Department:</td>
                                                    <td class="text-right">{{ $resultUser->userDetail->department }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Designation:</td>
                                                    <td class="text-right">{{ $resultUser->userDetail->designation }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status:</td>
                                                    <td class="text-right">
                                                        @switch($resultUser->status)
                                                            @case(\Modules\User\enum\UserStatus::ACTIVE)
                                                            {{ \Modules\User\enum\UserStatus::USER_STATUS[$resultUser->status] }}
                                                            @break
                                                            @case(\Modules\User\enum\UserStatus::INACTIVE)
                                                            {{ \Modules\User\enum\UserStatus::USER_STATUS[$resultUser->status] }}
                                                            @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            @include('layouts.alert')
                                            <h5 class="mb-3"><i class="fas fa-edit m-r-5"></i> Update your profile</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-md-8 offset-2">
                                                    <form id="form-profile-edit" method="post" action="{{ route('user.profile.update') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="first_name" name="first_name" value="{{ $resultUser->userDetail->first_name }}" placeholder="Enter first name">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="last_name" name="last_name" value="{{ $resultUser->userDetail->last_name }}" placeholder="Enter last name">
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-square float-right">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-3"><i class="fas fa-edit m-r-5"></i> Change Password</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-md-8 offset-2">
                                                    <form id="form-change-password" method="post" action="{{ route('user.change-password') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="password">New Password<span class="text-danger">*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control btn-square" id="password" name="password" placeholder="Enter new password">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="feather icon-eye-off" style="cursor: pointer;" onclick="showPassword('password', this);"></i></span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control btn-square" id="confirm_password" name="confirm_password" placeholder="Confirm password">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="feather icon-eye-off" style="cursor: pointer;" onclick="showPassword('confirm_password', this);"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-dark btn-square float-right">Change Password</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ task-detail ] end -->
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
        function showPassword(id, ele)
        {
            if($("#"+id).attr('type') == 'text') {
                $("#"+id).attr('type', 'password');
                $(ele).removeClass('icon-eye').addClass('icon-eye-off');
            } else {
                $("#"+id).attr('type', 'text');
                $(ele).removeClass('icon-eye-off').addClass('icon-eye');
            }
        }
        $(function(){
            $("#form-profile-edit").validate({
                focusInvalid: false,
                rules: {
                    'first_name' : { required : true },
                    'last_name' : { required : true }
                },
                messages: {
                    'first_name' : { required: "Please enter first name" },
                    'last_name' : { required: "Please enter last name" }
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

            $.validator.addMethod(
                "regex",
                function(value, element) {
                    var re = new RegExp("^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$");
                    return this.optional(element) || re.test(value);
                },
                "The password must contain a minimum of 1 lower case character, 1 upper case character, 1 digit and 1 special character"
            );

            $("#form-change-password").validate({
                focusInvalid: false,
                rules: {
                    password : {
                        required: true,
                        minlength : 8,
                        regex: true
                    },
                    confirm_password : {
                        required: true,
                        equalTo : "#password"
                    }
                },
                messages: {
                    'password' : {
                        required: "Please enter first name",
                        minlength: "The new password must be at least 8 characters long",
                    },
                    'confirm_password' : {
                        required: "Please enter last name",
                        equalTo: "Confirm password should be same as new password",
                    }
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
