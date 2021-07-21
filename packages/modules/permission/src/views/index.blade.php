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
                                        <h5 class="m-b-10">Module Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Module Management</a></li>
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
                                            <h5>Modules</h5>
                                            <div class="float-right">
                                                <a href="{{ route('permission.create') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New Module</button></a>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table_roles" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($resultPermissions as $permission)
                                                        <tr>
                                                            <td>{{ $permission->name }}</td>
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
