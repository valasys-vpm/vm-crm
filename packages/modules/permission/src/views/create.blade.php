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
                                    <h5 class="m-b-10">Module Management</h5>
                                </div>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('role') }}">Module Management</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Create New Module</a></li>
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
                                        <h5>Create New Module</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form id="form-role-create" method="post" action="{{ route('permission.store') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label for="name">Module Name</label>
                                                            <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter module name" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="slug">Slug</label>
                                                            <input type="text" class="form-control btn-square" id="slug" name="slug" placeholder="Enter module slug" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="status">Select Parent</label>
                                                            <select class="form-control btn-square" id="parent_id" name="parent_id">
                                                                <option value="">-- Select Parent Module --</option>
                                                                @foreach($resultPermissions as $permission)
                                                                    <option value="{{ base64_encode($permission->id) }}">{{ $permission->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="icon">Route Name</label>
                                                            <input type="text" class="form-control btn-square" id="route" name="route" placeholder="Enter route name">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="name">Icon</label>
                                                            <input type="text" class="form-control btn-square" id="icon" name="icon" placeholder="Enter icon class">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="sidebar_visibility">Show On Sidebar</label>
                                                            <br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline1" name="sidebar_visibility" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadioInline1">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline2" name="sidebar_visibility" value="0" class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="customRadioInline2">No</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="priority">Priority</label>
                                                            <input type="number" class="form-control btn-square" id="priority" name="priority" placeholder="Enter priority" value="1" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="is_module">Is Module</label>
                                                            <br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline3" name="is_module" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="customRadioInline3">Yes</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline4" name="is_module" value="0" class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="customRadioInline4">No</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label for="status">Status</label>
                                                            <br>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline5" name="status" value="1" class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="customRadioInline5">Active</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="customRadioInline6" name="status" value="0" class="custom-control-input" >
                                                                <label class="custom-control-label" for="customRadioInline6">Inactive</label>
                                                            </div>
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
        $("#form-role-create").validate({
            focusInvalid: false,
            rules: {
                'name' : {
                    required : true,
                    remote : {
                        url : '{{ route('role.validate.name') }}',
                        data : {
                                    name : function(){
                                        return $("#name").val();
                                    }
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
