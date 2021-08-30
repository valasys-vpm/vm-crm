@extends('layouts.master')
@section('stylesheet')
@parent
<!-- select2 css -->
<link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">
<!-- material datetimepicker css -->
<link rel="stylesheet"
    href="{{ asset('public/template/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

<style>
    .card-customer i {
        width: 45px !important;
        height: 45px !important;
    }

    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>
@append

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
                                    <h5 class="m-b-10">Valasys Media Campaign Overview</h5>
                                </div>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
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
                        @if(Auth::user()->role_id != '31')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fas fa-filter m-r-5"></i> Filters</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button style="display: none;" type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <button type="button" class="btn minimize-card"
                                                    id="filter-card-toggle"><i class="feather icon-plus"></i></button>
                                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right"
                                                    style="display: none;">
                                                    <li class="dropdown-item full-card"><a href="#!"><span><i
                                                                    class="feather icon-maximize"></i>
                                                                maximize</span><span style="display:none"><i
                                                                    class="feather icon-minimize"></i>
                                                                Restore</span></a></li>
                                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                                    class="feather icon-minus"></i> collapse</span><span
                                                                style="display:none"><i class="feather icon-plus"></i>
                                                                expand</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block" style="display: none;">
                                        <form id="form-campaign-filters">
                                            <div class="row">
                                                <div class="col-md-2 form-group">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="text" class="form-control btn-square p-1 pl-2"
                                                        id="start_date" name="start_date"
                                                        placeholder="Select Start Date">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="end_date">End Date</label>
                                                    <input type="text" class="form-control btn-square p-1 pl-2"
                                                        id="end_date" name="end_date" placeholder="Select End Date">
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="campaign_status">Status</label>
                                                    <select class="form-control btn-square p-1 pl-2 select2-multiple"
                                                        id="campaign_status" name="campaign_status[]"
                                                        style="height: unset;" multiple>
                                                        @foreach(\Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS
                                                        as $campaign_status => $value)
                                                        <option value="{{ $campaign_status }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="delivery_day">Delivery Day</label>
                                                    <select class="form-control btn-square select2-multiple"
                                                        id="delivery_day" name="delivery_day[]" style="height: unset;"
                                                        multiple="multiple">
                                                        <option value="1" data-abbreviation="Mon"> Monday</option>
                                                        <option value="2" data-abbreviation="Tue"> Tuesday</option>
                                                        <option value="3" data-abbreviation="Wed"> Wednesday</option>
                                                        <option value="4" data-abbreviation="Thu"> Thursday</option>
                                                        <option value="5" data-abbreviation="Fri"> Friday</option>
                                                        <option value="6" data-abbreviation="Sat"> Saturday</option>
                                                        <option value="0" data-abbreviation="Sun"> Sunday</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="due_in">Due In</label>
                                                    <select class="form-control btn-square p-1 pl-2" id="due_in"
                                                        name="due_in" style="height: unset;">
                                                        <option value=""> -- Select -- </option>
                                                        <option value="Today">Today</option>
                                                        <option value="Tomorrow">Tomorrow</option>
                                                        <option value="7 Days">7 Days</option>
                                                        <option value="Past Due">Past Due</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="country_id">Country(s)</label>
                                                    <select class="form-control btn-square select2-multiple"
                                                        id="country_id" name="country_id[]" multiple="multiple"
                                                        style="height: unset;">
                                                        @foreach($resultCountries as $country)
                                                        <option value="{{$country->id}}"
                                                            data-region-id="{{$country->region_id}}">
                                                            {{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="region_id">Region(s)</label>
                                                    <select class="form-control btn-square select2-multiple"
                                                        id="region_id" name="region_id[]" multiple="multiple"
                                                        style="height: unset;">
                                                        @foreach($resultRegions as $region)
                                                        <option value="{{$region->id}}"
                                                            data-abbreviation="{{ $region->abbreviation }}">
                                                            {{ $region->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="campaign_type_id">Campaign Type</label>
                                                    <select class="form-control btn-square p-1 pl-2"
                                                        id="campaign_type_id" name="campaign_type_id"
                                                        style="height: unset;">
                                                        <option value="">-- Select Campaign Type --</option>
                                                        @foreach($resultCampaignTypes as $campaign_type)
                                                        <option value="{{$campaign_type->id}}">
                                                            {{ $campaign_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 form-group">
                                                    <label for="campaign_filter_id">Campaign Filter</label>
                                                    <select class="form-control btn-square p-1 pl-2"
                                                        id="campaign_filter_id" name="campaign_filter_id"
                                                        style="height: unset;">
                                                        <option value="">-- Select Campaign Filter --</option>
                                                        @foreach($resultCampaignFilters as $campaign_filter)
                                                        <option value="{{$campaign_filter->id}}">
                                                            {{ $campaign_filter->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button id="button-filter-reset" type="reset"
                                                        class="btn btn-outline-dark btn-square btn-sm"><i
                                                            class="fas fa-undo m-r-5"></i>Reset</button>
                                                    <button id="button-filter-apply" type="button"
                                                        class="btn btn-outline-primary btn-square btn-sm"><i
                                                            class="fas fa-filter m-r-5"></i>Apply</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding: 0px 15px;margin-bottom: 30px;">

                            @foreach(\Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS as $key => $status)

                            <div class="col-md-4" style="padding: 4px !important;">
                                <div class="card card-customer shadow" style="margin-bottom: 2px;">
                                    <div class="card-block" style="padding: 10px 25px !important;">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col">
                                                <h2 class="mb-2 f-w-300 lead-counts" id="count-{{$status}}">0</h2>
                                                <h5 class="text-muted mb-0">{{ $status }}</h5>
                                            </div>
                                            <div class="col-auto">
                                                @switch($status)
                                                @case('Live') <i
                                                    class="feather icon-play f-20 text-white bg-success shadow"></i>
                                                @break
                                                @case('Paused') <i
                                                    class="feather icon-pause f-20 text-white bg-warning shadow"></i>
                                                @break
                                                @case('Cancelled') <i
                                                    class="feather icon-x f-20 text-white bg-danger shadow"></i>
                                                @break
                                                @case('Delivered') <i
                                                    class="feather icon-check f-20 text-white bg-info shadow"></i>
                                                @break
                                                @case('Reactivated') <i
                                                    class="feather icon-refresh-cw f-20 text-white bg-success shadow"></i>
                                                @break
                                                @case('Shortfall') <i
                                                    class="feather icon-chevrons-down f-20 text-white bg-secondary shadow"></i>
                                                @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <div class="row" style="display: none;">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Chart 1</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                    <li class="dropdown-item full-card"><a href="#!"><span><i
                                                                    class="feather icon-maximize"></i>
                                                                maximize</span><span style="display:none"><i
                                                                    class="feather icon-minimize"></i>
                                                                Restore</span></a></li>
                                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                                    class="feather icon-minus"></i> collapse</span><span
                                                                style="display:none"><i class="feather icon-plus"></i>
                                                                expand</span></a></li>
                                                    <li class="dropdown-item"><a href="#!"><i
                                                                class="feather icon-refresh-cw"></i> reload</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <div id="am-pie-2" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->role_id != '34')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Chart 2</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="feather icon-more-vertical"></i>
                                                </button>
                                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                    <li class="dropdown-item full-card"><a href="#!"><span><i
                                                                    class="feather icon-maximize"></i>
                                                                maximize</span><span style="display:none"><i
                                                                    class="feather icon-minimize"></i>
                                                                Restore</span></a></li>
                                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                                    class="feather icon-minus"></i> collapse</span><span
                                                                style="display:none"><i class="feather icon-plus"></i>
                                                                expand</span></a></li>
                                                    <li class="dropdown-item"><a href="#!"><i
                                                                class="feather icon-refresh-cw"></i> reload</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <div id="chartdiv"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif
                        @endif
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<!-- select2 Js -->
<script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- material datetimepicker Js -->
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script
    src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
</script>

<!-- am chart js -->
<!--    <script src="{{ asset('public/template') }}/assets/plugins/chart-am4/js/core.js"></script>
    <script src="{{ asset('public/template') }}/assets/plugins/chart-am4/js/charts.js"></script>
    <script src="{{ asset('public/template') }}/assets/plugins/chart-am4/js/animated.js"></script>
    <script src="{{ asset('public/template') }}/assets/plugins/chart-am4/js/maps.js"></script>
    <script src="{{ asset('public/template') }}/assets/plugins/chart-am4/js/worldLow.js"></script>-->

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script>
    /* Initializations */
    $(function() {
        $('#start_date').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'D-MMM-YYYY'
        }).on('change', function(e, date) {
            $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);
        });
        $('#end_date').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'D-MMM-YYYY'
        });
        $("#campaign_status").select2({
            placeholder: " -- Select Status(s) --"
        });
        $("#delivery_day").select2({
            placeholder: " -- Select Day(s) --",
            templateSelection: function(a) {
                return !!$(a.element).data("abbreviation") && $(a.element).data("abbreviation")
            }
        });
        $("#country_id").select2({
            placeholder: " -- Select Country(s) --"
        });
        $("#region_id").select2({
            placeholder: " -- Select Region(s) --",
            templateSelection: function(a) {
                return !!$(a.element).data("abbreviation") && $(a.element).data("abbreviation")
            }
        });
    });
</script>

<script>
    $(function() {
        $("#filter-card-toggle").click(function() {
            if ($(this).children('i').attr('class') == 'feather icon-minus') {
                $(this).children('i').removeClass('icon-minus').addClass('icon-plus');
            } else {
                $(this).children('i').removeClass('icon-plus').addClass('icon-minus');
            }
        });
    });
</script>

<script>
    $(function() {
        getDashboardData();
        //initChart();
    });

    function getDashboardData() {
        $.ajax({
            url: BASE_PATH + "/dashboard/get-data-v3",
            dataType: 'JSON',
            data: {
                /*'month': $('#campaign-status-month-select').val(),
                'start_date': $('#campaign-status-start-date').val(),
                'end_date': $('#campaign-status-end-date').val()*/
            },
            beforeSend: function() {
                $("#lead-counts").text(0);
            },
            success: function(response) {
                if (Object.keys(response.data).length > 0) {
                    $.each(response.data, function(status, item) {
                        $('#count-' + status).text(item.count);
                    });
                }
                if (Object.keys(response.chartData).length > 0) {
                    initChart(response.chartData);
                    initChartRadial(response.chartData);
                }
            }
        });
    }

    function initChart(chartData) {
        var chart = am4core.create("am-pie-2", am4charts.PieChart);
        var chartArray = [];
        $.each(chartData, function(key, value) {
            chartArray.push(value)
        });
        chart.data = chartArray;
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "count";
        pieSeries.dataFields.category = "status";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;
        chart.legend = new am4charts.Legend();
        $('body').find("[aria-labelledby='id-61-title']").remove();
    }

    function initChartRadial(chartData) {
        am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end
            var container = am4core.create("chartdiv", am4core.Container);
            container.width = am4core.percent(100);
            container.height = am4core.percent(100);
            container.layout = "horizontal";
            var chart = container.createChild(am4charts.PieChart);
            // Add data
            var chartArray = [];
            $.each(chartData, function(key, value) {
                chartArray.push(value);
            });
            chart.data = chartArray;
            // Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "count";
            pieSeries.dataFields.category = "status";
            pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;
            pieSeries.labels.template.text = "{category}\n{value.percent.formatNumber('#.#')}%";
            pieSeries.slices.template.events.on("hit", function(event) {
                selectSlice(event.target.dataItem);
            });
            chart.legend = new am4charts.Legend();
            var chart2 = container.createChild(am4charts.PieChart);
            chart2.width = am4core.percent(30);
            chart2.radius = am4core.percent(80);
            // Add and configure Series
            var pieSeries2 = chart2.series.push(new am4charts.PieSeries());
            pieSeries2.dataFields.value = "value";
            pieSeries2.dataFields.category = "name";
            pieSeries2.slices.template.states.getKey("active").properties.shiftRadius = 0;
            //pieSeries2.labels.template.radius = am4core.percent(100);
            //pieSeries2.labels.template.inside = true;
            //pieSeries2.labels.template.fill = am4core.color("#ffffff");
            pieSeries2.labels.template.disabled = true;
            pieSeries2.ticks.template.disabled = true;
            pieSeries2.alignLabels = false;
            pieSeries2.events.on("positionchanged", updateLines);
            var interfaceColors = new am4core.InterfaceColorSet();
            var line1 = container.createChild(am4core.Line);
            line1.strokeDasharray = "2,2";
            line1.strokeOpacity = 0.5;
            line1.stroke = interfaceColors.getFor("alternativeBackground");
            line1.isMeasured = false;
            var line2 = container.createChild(am4core.Line);
            line2.strokeDasharray = "2,2";
            line2.strokeOpacity = 0.5;
            line2.stroke = interfaceColors.getFor("alternativeBackground");
            line2.isMeasured = false;
            var selectedSlice;

            function selectSlice(dataItem) {
                selectedSlice = dataItem.slice;
                var fill = selectedSlice.fill;
                var count = dataItem.dataContext.subData.length;
                pieSeries2.colors.list = [];
                for (var i = 0; i < count; i++) {
                    pieSeries2.colors.list.push(fill.brighten(i * 2 / count));
                }
                chart2.data = dataItem.dataContext.subData;
                pieSeries2.appear();
                var middleAngle = selectedSlice.middleAngle;
                var firstAngle = pieSeries.slices.getIndex(0).startAngle;
                var animation = pieSeries.animate([{
                    property: "startAngle",
                    to: firstAngle - middleAngle
                }, {
                    property: "endAngle",
                    to: firstAngle - middleAngle + 360
                }], 600, am4core.ease.sinOut);
                animation.events.on("animationprogress", updateLines);
                selectedSlice.events.on("transformed", updateLines);
                //  var animation = chart2.animate({property:"dx", from:-container.pixelWidth / 2, to:0}, 2000, am4core.ease.elasticOut)
                //  animation.events.on("animationprogress", updateLines)
            }

            function updateLines() {
                if (selectedSlice) {
                    var p11 = {
                        x: selectedSlice.radius * am4core.math.cos(selectedSlice.startAngle),
                        y: selectedSlice.radius * am4core.math.sin(selectedSlice.startAngle)
                    };
                    var p12 = {
                        x: selectedSlice.radius * am4core.math.cos(selectedSlice.startAngle + selectedSlice
                            .arc),
                        y: selectedSlice.radius * am4core.math.sin(selectedSlice.startAngle + selectedSlice
                            .arc)
                    };
                    p11 = am4core.utils.spritePointToSvg(p11, selectedSlice);
                    p12 = am4core.utils.spritePointToSvg(p12, selectedSlice);
                    var p21 = {
                        x: 0,
                        y: -pieSeries2.pixelRadius
                    };
                    var p22 = {
                        x: 0,
                        y: pieSeries2.pixelRadius
                    };
                    p21 = am4core.utils.spritePointToSvg(p21, pieSeries2);
                    p22 = am4core.utils.spritePointToSvg(p22, pieSeries2);
                    line1.x1 = p11.x;
                    line1.x2 = p21.x;
                    line1.y1 = p11.y;
                    line1.y2 = p21.y;
                    line2.x1 = p12.x;
                    line2.x2 = p22.x;
                    line2.y1 = p12.y;
                    line2.y2 = p22.y;
                }
            }
            chart.events.on("datavalidated", function() {
                setTimeout(function() {
                    selectSlice(pieSeries.dataItems.getIndex(0));
                }, 1000);
            });
            $('body').find("[aria-labelledby='id-124-title']").remove();
        }); // end am4core.ready()
    }
</script>

<!-- Chart code -->
<script>

</script>

@endsection
