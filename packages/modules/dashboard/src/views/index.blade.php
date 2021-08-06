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
                                                    <select onchange="getDashboardData();" name=""
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
                                                    <input onchange="getDashboardData();" type="text" name=""
                                                        id="campaign-status-start-date" class="form-control"
                                                        placeholder="Start Date">
                                                </div>
                                                <div class="col-md-4">
                                                    <input onchange="getDashboardData();" type="text" name=""
                                                        id="campaign-status-end-date" class="form-control"
                                                        placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" id="donut-block">
                                        <div class="col-md-3 text-center">
                                            <input id="Delivered-donut" type="text" class="dial" value="50" data-width="200"
                                                data-height="200" data-linecap="round" data-displayprevious="true"
                                                data-displayInput="true" data-readonly="true" data-fgColor="#66bb6a">
                                            <br>
                                            <h5 class="text-center">Completed</h5>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <input id="Live-donut" type="text" class="dial" value="48" data-width="200"
                                                data-height="200" data-linecap="round" data-displayprevious="true"
                                                data-displayInput="true" data-readonly="true" data-fgColor="#f4c22b">
                                            <br>
                                            <h5 class="text-center">Live</h5>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <input id="Paused-donut" type="text" class="dial" value="48" data-width="200"
                                                data-height="200" data-linecap="round" data-displayprevious="true"
                                                data-displayInput="true" data-readonly="true" data-fgColor="#748892">
                                            <br>
                                            <h5 class="text-center">Paused</h5>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <input id="Cancelled-donut" type="text" class="dial" value="48" data-width="200"
                                                data-height="200" data-linecap="round" data-displayprevious="true"
                                                data-displayInput="true" data-readonly="true" data-fgColor="#37474f">
                                            <br>
                                            <h5 class="text-center">Cancelled</h5>
                                        </div>
                                    </div>
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

            getDashboardData();
        });

        function getDashboardData() {
            $.ajax({

                url: BASE_PATH + "/dashboard/get-data",
                dataType: 'JSON',
                data: {
                    'month': $('#campaign-status-month-select').val(),
                    'start_date': $('#campaign-status-start-date').val(),
                    'end_date': $('#campaign-status-end-date').val()
                },
                beforeSend: function() {

                    $('#Live-lead-count').text(0);
                    $('#Delivered-lead-count').text(0);
                    $('#Paused-lead-count').text(0);
                    $('#Cancelled-lead-count').text(0);

                    $('#donut-block .dial').val(0).trigger('change');
                },
                success: function(response) {

                    if (Object.keys(response.data).length > 0) {

                        if ('undefined' != typeof response.data.count) {

                            $.each(response.data.count, function(status, count) {

                                $('#' + status + '-lead-count').text(count);
                            });
                        }
                        if ('undefined' != typeof response.data.percentage) {

                            $.each(response.data.percentage, function(status, percentage) {

                                $('#' + status + '-donut').val(percentage).trigger('change');
                            });
                        }
                    }
                }
            });
        }
    </script>
@endsection
