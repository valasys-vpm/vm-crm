@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
    <!-- toolbar css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/toolbar/css/jquery.toolbar.css')}}">
@append

@section('content')
    <section class="pcoded-main-container">
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">User Management</a></li>
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
                                <!-- [ configuration table ] start -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            @include('layouts.alert')
                                            <h5>Users</h5>
                                            <div class="float-right">
                                                @if(Helper::hasPermission('user.create'))<a href="{{ route('user.create') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New User</button></a>@endif
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table_users" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Login Status</th>
                                                        <th>Status</th>
                                                        @if(Helper::hasPermission('user.show') || Helper::hasPermission('user.edit') || Helper::hasPermission('user.destroy') || Helper::hasPermission('user.logout.force'))
                                                        <th style="width: 20%;text-align: center;">Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($resultUsers as $user)
                                                        <tr>
                                                            <td>{{ strtoupper($user->userDetail->emp_code) }}</td>
                                                            <td><span>{{ $user->userDetail->first_name.' '.$user->userDetail->last_name }}</span></td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->role->name }}</td>

                                                            <td class="text-center">
                                                            @if(isset($user->logged_on) && (round(abs(strtotime($user->logged_on) - strtotime(date('Y-m-d H:i:s'))) / 60,2) <= config('session.lifetime')))
                                                                <i class="fas fa-circle text-c-green m-r-15" style="font-size: 20px;" title="Online" data-toggle="tooltip" data-placement="top"></i>
                                                            @else
                                                                <i class="fas fa-circle text-c-red m-r-15" style="font-size: 20px;" title="Offline" data-toggle="tooltip" data-placement="top"></i>
                                                            @endif
                                                            </td>
                                                            <td>
                                                                @switch($user->status)
                                                                    @case(\Modules\User\enum\UserStatus::ACTIVE)
                                                                    <span class="badge badge-pill badge-success">{{ \Modules\User\enum\UserStatus::USER_STATUS[$user->status] }}</span>
                                                                    @break
                                                                    @case(\Modules\User\enum\UserStatus::INACTIVE)
                                                                    <span class="badge badge-pill badge-danger">{{ \Modules\User\enum\UserStatus::USER_STATUS[$user->status] }}</span>
                                                                    @break
                                                                @endswitch
                                                            </td>
                                                            @if(Helper::hasPermission('user.show') || Helper::hasPermission('user.edit') || Helper::hasPermission('user.destroy') || Helper::hasPermission('user.logout.force') || Helper::hasPermission('user.reset_password'))
                                                            <td style="text-align: center;">
                                                                <div id="toolbar-options-{{$user->id}}" class="hidden" style="border-radius: 0 !important;">
                                                                    @if(Helper::hasPermission('user.show'))<a href="{{ route('user.show', base64_encode($user->id)) }}" onclick="javascript: location.href = '{{ route('user.show', base64_encode($user->id)) }}';" title="View Details" style="border-radius: 0;"><i class="feather icon-eye"></i></a>@endif
                                                                    @if(Helper::hasPermission('user.reset_password'))<a href="#" onclick="resetPassword('{{base64_encode($user->id)}}')" title="Reset Password" style="border-radius: 0;" data-toggle="modal"><i class="feather icon-lock"></i></a>@endif
                                                                    @if(Helper::hasPermission('user.edit'))<a href="{{ route('user.edit', base64_encode($user->id)) }}" onclick="javascript: location.href = '{{ route('user.edit', base64_encode($user->id)) }}';" title="Edit" style="border-radius: 0;"><i class="feather icon-edit"></i></a>@endif
                                                                    @if(Helper::hasPermission('user.destroy'))<a href="{{ route('user.destroy', base64_encode($user->id)) }}" title="Delete" onclick="javascript:if(confirm('Are you sure to delete user?')){location.href = '{{ route('user.destroy', base64_encode($user->id)) }}';};" style="border-radius: 0;"><i class="feather icon-trash-2"></i></a>@endif
                                                                    @if(Helper::hasPermission('user.logout.force') && isset($user->logged_on) && (round(abs(strtotime($user->logged_on) - strtotime(date('Y-m-d H:i:s'))) / 60,2) <= config('session.lifetime')))
                                                                        <a href="{{ route('user.logout.force', base64_encode($user->id)) }}" title="Logout User" onclick="javascript:if(confirm('Are you sure to logout user?')){location.href = '{{ route('user.logout.force', base64_encode($user->id)) }}';};" style="border-radius: 0;"><i class="feather icon-log-out"></i></a>
                                                                    @endif
                                                                </div>
                                                                <div data-toolbar="user-options" class="btn-toolbar btn-default btn-toolbar-light dark-toolbar d-inline-flex" id="dark-toolbar-{{$user->id}}" data-id="{{$user->id}}" title="More Action"><i class="feather icon-settings"></i></div>
                                                            </td>
                                                            @endif
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            No Data Found
                                                        </tr>
                                                    @endforelse

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ configuration table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modal-reset-password" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form id="form-reset-password" method="post" action="{{ route('user.reset_password') }}">
                            @csrf
                            <input type="hidden" id="reset_password_user_id" name="user_id" value="">
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
                            <button id="reset-password-submit" type="submit" class="btn btn-primary btn-square float-right">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    @parent
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script>
        $('#table_users').DataTable();
        // [ dark-toolbar ]
        $(function (){
            $('.dark-toolbar').each(function() {
                var id = $(this).data('id');
                $(this).toolbar({
                    content: '#toolbar-options-' + id,
                    position: 'left',
                    style: 'dark'
                });
            });

            $.validator.addMethod(
                "regex",
                function(value, element) {
                    var re = new RegExp("^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$");
                    return this.optional(element) || re.test(value);
                },
                "The password must contain a minimum of 1 lower case character, 1 upper case character, 1 digit and 1 special character"
            );

            $("#form-reset-password").validate({
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
                        required: "Please enter password.",
                        minlength: "The new password must be at least 8 characters long.",
                    },
                    'confirm_password' : {
                        required: "Please confirm your password.",
                        equalTo: "Confirm password should be same as new password.",
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

        function resetPassword(user_id) {
            $("#reset_password_user_id").val(user_id);
            $("#modal-reset-password").modal('show');
        }

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
    </script>
@append
