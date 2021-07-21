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
                                        <h5 class="m-b-10">Role Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('role') }}">Role Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Edit Role</a></li>
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
                                            <h5>Edit Role</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-role-edit" method="post" action="{{ route('role.update', base64_encode($resultRole->id)) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="name">Role Name</label>
                                                                <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter role name" value="{{ $resultRole->name }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="status">Status</label>
                                                                <select class="form-control btn-square" id="status" name="status">
                                                                    @foreach(\Modules\Role\enum\RoleStatus::ROLE_STATUS as $role_status => $value)
                                                                        <option value="{{ $role_status }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-square">Update</button>
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

        $("#status").val({{$resultRole->status}});

        $(function(){
            $("#form-role-edit").validate({
                focusInvalid: false,
                rules: {
                    'name' : {
                        required : true,
                        remote : {
                            url : '{{ route('role.validate.name') }}',
                            data : {
                                name : function(){
                                    return $("#name").val();
                                },
                                role_id : '{{ base64_encode($resultRole->id) }}'
                            }
                        }
                    }
                },
                messages: {
                    'name' : {
                        required: "Please enter role name",
                        remote: "Role name already exists"
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
