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
                                        <h5 class="m-b-10">History</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">History</a></li>
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
                                            <h5>Histories</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-histories" class="display table nowrap table-striped table-hover" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Action</th>
                                                        <th>Message</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    </thead>
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
        $('#table-histories').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('history.get_histories')}}",
            },
            "columns": [
                { render: function (data, type, row) {
                    return row.user.user_detail.full_name;
                    } },
                { data: 'action' },
                { data: 'message' },
                { data: 'display_date' }
            ]
        });
    </script>
@append
