@extends('layouts.master')
@section('stylesheet')
    <!-- material datetimepicker css -->
    <link rel="stylesheet"
        href="{{ asset('public/template/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
    <style>
        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
        }

        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
        }

        .card-counter.primary {
            background-color: #007bff;
            color: #FFF;
        }

        .card-counter.danger {
            background-color: #ef5350;
            color: #FFF;
        }

        .card-counter.success {
            background-color: #66bb6a;
            color: #FFF;
        }

        .card-counter.info {
            background-color: #26c6da;
            color: #FFF;
        }

        .card-counter i {
            font-size: 5em;
            opacity: 0.2;
        }

        .card-counter .count-numbers {
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
        }

        .card-counter .count-name {
            position: absolute;
            right: 35px;
            top: 65px;
            font-style: italic;
            text-transform: capitalize;
            opacity: 0.5;
            display: block;
            font-size: 18px;
        }

        .radial-bar-xl {
            width: 170px !important;
            height: 170px !important;
            font-size: 30px !important;
        }

        .radial-bar-xl>img,
        .radial-bar-xl:after {
            width: 120px !important;
            height: 120px !important;
            margin-left: 25px !important;
            margin-top: 25px !important;
            line-height: 120px !important;
        }

        canvas {
            width: 199px !important;
        }

    </style>
@endsection
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
                                        <h5 class="m-b-10">Dashboard</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html"><i
                                                    class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h4>Campaign Status</h4>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select onchange="getdashboarddataV2(2);" name=""
                                                        id="campaign-status-month-select" class="form-control">
                                                        <option value="">-- Select Month --</option>
                                                        <option value="1">January</option>
                                                        <option value="2">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input onchange="getdashboarddataV2(2);" type="text"
                                                        id="campaign-status-start-date" class="form-control"
                                                        placeholder="Start Date">
                                                </div>
                                                <div class="col-md-4">
                                                    <input onchange="getdashboarddataV2(2);" type="text" name=""
                                                        id="campaign-status-end-date" class="form-control"
                                                        placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        {{-- <div class="col-md-3">
                                            <div class="card-counter primary">
                                                <i class="fa fa-database"></i>
                                                <span class="count-numbers" id="Live-lead-count">0</span>
                                                <span class="count-name">Live</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card-counter danger">
                                                <i class="fa fa-database"></i>
                                                <span class="count-numbers" id="Delivered-lead-count">0</span>
                                                <span class="count-name">Delivered</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card-counter success">
                                                <i class="fa fa-database"></i>
                                                <span class="count-numbers" id="Paused-lead-count">0</span>
                                                <span class="count-name">Paused</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card-counter info">
                                                <i class="fa fa-database"></i>
                                                <span class="count-numbers" id="Cancelled-lead-count">0</span>
                                                <span class="count-name">Cancelled</span>
                                            </div>
                                        </div> --}}

                                        <!-- [ Application list ] end -->
                                    </div>

                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12">
                                <div class="card Application-list">
                                    <div class="card-header">
                                        <h5>Campaign status count</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-horizontal"></i>
                                                </button>
                                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                    <li class="dropdown-item full-card"><a href="#!"><span><i
                                                                    class="feather icon-maximize"></i> maximize</span><span
                                                                style="display:none"><i class="feather icon-minimize"></i>
                                                                Restore</span></a></li>
                                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                                    class="feather icon-minus"></i> collapse</span><span
                                                                style="display:none"><i class="feather icon-plus"></i>
                                                                expand</span></a></li>
                                                    <li class="dropdown-item reload-card"><a href="#!"><i
                                                                class="feather icon-refresh-cw"></i> reload</a></li>
                                                    <li class="dropdown-item close-card"><a href="#!"><i
                                                                class="feather icon-trash"></i> remove</a></li>
                                                </ul>

                                            </div>
                                            
                                        </div>
                                      
                                    </div>
                                    <div id="chart-highchart-bar1" style=""></div>
                                </div>
                                
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
    <!-- material datetimepicker Js -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

    <script
        src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>

    <!-- chart-knob Js -->
    <script src="{{ asset('public/template/assets/plugins/chart-knob/js/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('public/template/assets/plugins/chart-knob/js/jquery.knob-custom.min.js') }}"></script>

    <!-- highchart chart -->
    <script src="{{ asset('public/template/assets/plugins/chart-highchart/js/highcharts.js') }}"></script>
    <script src="{{ asset('public/template/assets/plugins/chart-highchart/js/highcharts-3d.js') }}"></script>
    {{-- <script src="{{ asset('public/template/assets/js/pages/chart-highchart-custom.js') }}"></script> --}}

    <script>
        $(function() {

            $('#campaign-status-start-date').bootstrapMaterialDatePicker({
                weekStart: 0,
                time: false,
                format: 'D-MMM-YYYY'
            }).on('change', function(e, date) {
                $('#campaign-status-end-date').bootstrapMaterialDatePicker('setMinDate', date);
                $('#campaign-status-end-date').val('');
            });
            $('#campaign-status-end-date').bootstrapMaterialDatePicker({
                weekStart: 0,
                time: false,
                format: 'D-MMM-YYYY'
            });

        });

       
    </script>



@endsection
