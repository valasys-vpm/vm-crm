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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Manage Permission</a></li>
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
                                            <h5>Manage Permission for - {{ $resultRole->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-role-manage-permission" method="post" action="{{ route('role.manage_permission.store', base64_encode($resultRole->id)) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary btn-square float-right"><i class="feather icon-save"></i> Save</button>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-12 col-md-12  mb-5">
                                                                @forelse($resultPermissions as $permission)
                                                                    <div class="form-group mb-0">
                                                                        <div class="checkbox checkbox-primary checkbox-primary-outline d-inline">
                                                                            <input type="checkbox" class="cb_parent checkbox_{{$permission->id}}" data-id="{{$permission->id}}" id="p_{{$permission->id}}">
                                                                            <label for="p_{{$permission->id}}" class="cr text-dark" style="font-size: 18px;">{{ $permission->name }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <hr class="mt-0">
                                                                        <div class="row ml-5">
                                                                            <div class=" col-md-3 form-group d-inline">
                                                                                <div class="checkbox checkbox-danger checkbox-danger-outline d-inline">
                                                                                    <input type="checkbox" class="cb_child child_checkbox_{{$permission->id}}" data-parent-id="{{$permission->id}}" name="ids[]" value="{{ $permission->id }}" id="{{$permission->slug}}" @if(isset($permission->rolePermissions) && $permission->rolePermissions->count()) checked @endif>
                                                                                    <label for="{{$permission->slug}}" class="cr"> Browse</label>
                                                                                </div>
                                                                            </div>
                                                                        @forelse($permission->subPermissions as $subPermission)
                                                                            @if(!$subPermission->is_module)
                                                                            <div class=" col-md-3 form-group d-inline">
                                                                                <div class="checkbox checkbox-danger checkbox-danger-outline d-inline">
                                                                                    <input type="checkbox" class="cb_child child_checkbox_{{$permission->id}}" data-parent-id="{{$permission->id}}" name="ids[]" value="{{ $subPermission->id }}" id="{{$subPermission->slug}}" @if(isset($subPermission->rolePermissions) && $subPermission->rolePermissions->count()) checked @endif>
                                                                                    <label for="{{$subPermission->slug}}" class="cr">{{ $subPermission->name }}</label>
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                        @empty
                                                                        @endforelse
                                                                        </div>
                                                                        <br><br>
                                                                @empty
                                                                    <h5 class="text-danger">No Records Found</h5>
                                                                @endforelse
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-primary btn-square float-right"><i class="feather icon-save"></i> Save</button>
                                                            </div>
                                                        </div>
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
    <script>
        $(function(){
            $('.cb_parent').change(function (){
                var parent_id = $(this).data('id');
                if($(this).is(":checked")) {
                    $('.child_checkbox_'+parent_id).prop('checked', true);
                } else {
                    $('.child_checkbox_'+parent_id).prop('checked', false);
                }
            });

            $('.cb_child').change(function (){
                var parent_id = $(this).data('parent-id');
                if($('.child_checkbox_'+parent_id).length == $('.child_checkbox_'+parent_id+":checked").length) {
                    $('.checkbox_'+parent_id).prop('checked', true);
                } else {
                    $('.checkbox_'+parent_id).prop('checked', false);
                }
            });

            $('.cb_child').change();
        });
    </script>
@append
