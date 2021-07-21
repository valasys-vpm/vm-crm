@extends('layouts.master')

@section('stylesheet')
@parent
<!-- data tables css -->
<link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
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
                                        <h5 class="m-b-10">Role Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Role Management</a></li>
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
                                            <h5>Roles</h5>
                                            <div class="float-right">
                                                @if(Helper::hasPermission('role.create'))<a href="{{ route('role.create') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New Role</button></a>@endif
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table_roles" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th class="text-center">Status</th>
                                                        @if(Helper::hasPermission('role.edit') || Helper::hasPermission('role.destroy') || Helper::hasPermission('role.manage_permission'))
                                                        <th class="text-center" style="width: 20%;">Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($resultRoles as $role)
                                                        <tr>
                                                            <td>{{ $role->name }}</td>
                                                            <td class="text-center">
                                                                @switch($role->status)
                                                                    @case(\Modules\Role\enum\RoleStatus::ACTIVE)
                                                                    <span class="badge badge-pill badge-success">{{ \Modules\Role\enum\RoleStatus::ROLE_STATUS[$role->status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Role\enum\RoleStatus::INACTIVE)
                                                                    <span class="badge badge-pill badge-danger">{{ \Modules\Role\enum\RoleStatus::ROLE_STATUS[$role->status] }}</span>
                                                                    @break
                                                                @endswitch
                                                            </td>
                                                            @if(Helper::hasPermission('role.edit') || Helper::hasPermission('role.destroy') || Helper::hasPermission('role.manage_permission'))
                                                            <td class="text-center">
                                                                @if(Helper::hasPermission('role.edit'))<a href="{{ route('role.edit', base64_encode($role->id)) }}" class="btn btn-outline-primary btn-rounded btn-sm" title="Edit"><i class="feather icon-edit mr-0"></i></a>@endif
                                                                @if(Helper::hasPermission('role.destroy'))<a href="{{ route('role.destroy', base64_encode($role->id)) }}" class="btn btn-outline-danger btn-rounded btn-sm" title="Delete" onclick="javascript:return confirm('Are you sure to delete role - {{$role->name}} ?');"><i class="feather icon-trash-2 mr-0"></i></a>@endif
                                                                @if(Helper::hasPermission('role.manage_permission'))<a href="{{ route('role.manage_permission', base64_encode($role->id)) }}" class="btn btn-outline-dark btn-rounded btn-sm"><i class="feather icon-settings mr-0"></i> Manage Permission</a>@endif
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
@endsection

@section('javascript')
@parent
<!-- datatable Js -->
<script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
<script>
    $('#table_roles').DataTable();
</script>
@append
