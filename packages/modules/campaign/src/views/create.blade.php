@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">
    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css">
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
                                        <h5 class="m-b-10">Campaign Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('campaign') }}">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Add New Campaign</a></li>
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
                                            <h5>Add New Campaign</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-campaign-create" method="post" action="{{ route('campaign.store') }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="name">Campaign Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter campaign name">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="v_mail_campaign_id">V-Mail Campaign ID<span class="text-info"> <small>(Optional)</small></span></label>
                                                                <input type="text" class="form-control btn-square" id="v_mail_campaign_id" name="v_mail_campaign_id" placeholder="Enter v-mail campaign id">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="start_date">Start Date<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="start_date" name="start_date" placeholder="Select Start Date">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="end_date">End Date<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="end_date" name="end_date" placeholder="Select End Date">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="allocation">Allocation<span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control btn-square only-non-zero-number" id="allocation" name="allocation" placeholder="Enter allocation">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="campaign_status">Status</label>
                                                                <select class="form-control btn-square" id="campaign_status" name="campaign_status">
                                                                    @foreach(\Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS as $campaign_status => $value)
                                                                        <option value="{{ $campaign_status }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="pacing">Pacing</label>
                                                                <div class="form-control">
                                                                    <div class="form-group d-inline">
                                                                        <div class="radio radio-primary d-inline">
                                                                            <input type="radio" name="pacing" id="pacing_radio_1" value="Daily" class="pacing">
                                                                            <label for="pacing_radio_1" class="cr">Daily</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group d-inline">
                                                                        <div class="radio radio-primary d-inline">
                                                                            <input type="radio" name="pacing" id="pacing_radio_2" value="Monthly" class="pacing">
                                                                            <label for="pacing_radio_2" class="cr">Monthly</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 form-group">
                                                                <label for="total-sub-allocation">Total Sub-Allocation</label>
                                                                <br>
                                                                <span id="total-sub-allocation" class="h3">0</span><span id="text-allocation" class="h3"> / 0</span>
                                                            </div>

                                                            <div class="col-md-12 row" id="div_pacing_details" style="display: none;">
                                                                <div class="col-md-3 col-sm-12">
                                                                    <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-9 col-sm-12">
                                                                    <div class="tab-content" id="v-pills-tabContent">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="campaign_type_id">Campaign Type<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square" id="campaign_type_id" name="campaign_type_id">
                                                                    <option value="">-- Select Campaign Type --</option>
                                                                    @foreach($resultCampaignTypes as $campaign_type)
                                                                        <option value="{{$campaign_type->id}}">{{ $campaign_type->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="campaign_filter_id">Campaign Filter<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square" id="campaign_filter_id" name="campaign_filter_id">
                                                                    <option value="">-- Select Campaign Filter --</option>
                                                                    @foreach($resultCampaignFilters as $campaign_filter)
                                                                        <option value="{{$campaign_filter->id}}">{{ $campaign_filter->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="country_id">Country(s)<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square select2-multiple" id="country_id" name="country_id[]" multiple="multiple">
                                                                    @foreach($resultCountries as $country)
                                                                        <option value="{{$country->id}}" data-region-id="{{$country->region_id}}">{{ $country->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="region_id">Region(s)<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square select2-multiple" id="region_id" name="region_id[]" multiple="multiple" disabled>
                                                                    @foreach($resultRegions as $region)
                                                                        <option value="{{$region->id}}">{{ $region->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="specifications">Specifications</label>
                                                                <input type="file" class="form-control-file" id="specifications" name="specifications[]" multiple>
                                                            </div>

                                                            <div class="col-md-12 form-group">
                                                                <label for="note">Note</label>
                                                                <textarea id="note" name="note" class="form-control classic-editor" placeholder="Enter note here..." rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-square float-right">Save</button>
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
    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- material datetimepicker Js -->
    <script src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <!-- Ckeditor js -->
    <script src="{{ asset('public/template/assets/plugins/ckeditor/js/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(window).on('load', function() {
            // classic editor
            ClassicEditor.create(document.querySelector('.classic-editor'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
    <script>
        var monthArray = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var dayArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var holidays = @json($resultHolidays);

        $("#country_id").select2({
            placeholder: " -- Select Country(s) --",
        });
        $("#region_id").select2({
            placeholder: " -- Select Region(s) --",
        });
        $('#start_date').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'D-MMM-YYYY',
            switchOnClick : true,
        }).on('change', function(e, date) {
            $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);
        });
        $('#end_date').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'D-MMM-YYYY',
            switchOnClick : true,
        });
        $(function(){


            $('body').on("input", ".only-non-zero-number", function (){
                if(this.value < 1) {
                    $(this).val('');
                } else {
                    $(this).val(parseInt(this.value));
                }
            });
            $("#form-campaign-create").validate({
                ignore: [],
                focusInvalid: false,
                rules: {
                    'name' : { required : true },
                    'start_date' : { required : true },
                    'end_date' : { required : true },
                    'allocation' : { required : true },
                    'campaign_status' : { required : true },
                    'pacing' : { required : true },
                    'campaign_type_id' : { required : true },
                    'campaign_filter_id' : { required : true },
                    'country_id[]' : { required : true },
                    'v_mail_campaign_id' : {
                        required: false,
                        remote : {
                            url : '{{ route('campaign.validate.v_mail_campaign_id') }}'
                        }
                    }
                },
                messages: {
                    'name' : { required : "Please enter campaign name" },
                    'start_date' : { required : "Please select start date" },
                    'end_date' : { required : "Please select end date" },
                    'allocation' : { required : "Please enter allocation" },
                    'campaign_status' : { required : "Please select campaign status" },
                    'pacing' : { required : "Please select pacing" },
                    'campaign_type_id' : { required : "Please select campaign tye" },
                    'campaign_filter_id' : { required : "Please select campaign filter" },
                    'country_id[]' : { required : "Please select country(s)" },
                    'v_mail_campaign_id' : {
                        remote : "V-Mail Campaign Id already exists"
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
        $(function () {
            $("#country_id").change(function () {
                var regionIds = [];
                $.each($(this).children('option:selected'), function () {
                    regionIds.push($(this).data('region-id'));
                });
                $("#region_id").val(regionIds);
                $("#region_id").select2('destroy').select2({
                    placeholder: " -- Select Region(s) --",
                })
            });

            $('body').on('change', '#start_date, #end_date', function () {
                $('input[type=radio][name=pacing]').prop('checked', false);
                $("#v-pills-tab").html('');
                $("#div_pacing_details").hide();
                $("#v-pills-tabContent").html('');
                $("#total-sub-allocation").html(0);
            });

            $('input[type=radio][name=pacing]').change(function() {
                $("#v-pills-tab").html('');
                $("#div_pacing_details").hide();
                $("#v-pills-tabContent").html('');
                $("#total-sub-allocation").html(0);
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                if(start_date != '' && end_date != '') {
                    var start = new Date(start_date);
                    var end = new Date(end_date);
                    var month = '';
                    var html = '';

                    if($(this).val() == 'Daily') {
                        var start_loop_date = new Date(start_date);
                        while (start_loop_date <= end) {
                            month = monthArray[start_loop_date.getMonth()]+'-'+start_loop_date.getFullYear();
                            $("#v-pills-tab").append('<li><a class="nav-link text-left" id="v-pills-'+month+'-tab" data-toggle="pill" href="#v-pills-'+month+'" role="tab" aria-controls="v-pills-'+month+'" aria-selected="false">'+month+'</a></li>');
                            html = '<div class="tab-pane fade" id="v-pills-'+month+'" role="tabpanel" aria-labelledby="v-pills-'+month+'-tab">'+
                                '       <div class="row">'+
                                '           <div class="col-md-6 form-group">'+
                                '               <label for="days">Select Day(s)<span class="text-danger">*</span></label>'+
                                '               <select class="form-control btn-square select2-multiple select2-multiple-days" id="'+month+'_days" name="days['+month+'][]" multiple="multiple" data-month="'+start_loop_date.getMonth()+'" data-year="'+start_loop_date.getFullYear()+'" onChange="getHtmlPacingDates(this);">'+
                                '                   <option value="1"> Monday</option>'+
                                '                   <option value="2"> Tuesday</option>'+
                                '                   <option value="3"> Wednesday</option>'+
                                '                   <option value="4"> Thursday</option>'+
                                '                   <option value="5"> Friday</option>'+
                                '                   <option value="6"> Saturday</option>'+
                                '                   <option value="0"> Sunday</option>'+
                                '               </select>'+
                                '           </div>'+
                                '       </div>'+
                                '       <div class="row" id="'+month+'-dates">'+
                                '       </div>'+
                                '    </div>';
                            $("#v-pills-tabContent").append(html);
                            $(".select2-multiple-days").select2({
                                placeholder: " -- Select Day(s) --",
                            });
                            start_loop_date.setDate(1);
                            start_loop_date.setMonth( start_loop_date.getMonth() + 1 );
                        }
                    } else {
                        while (start <= end) {
                            month = monthArray[start.getMonth()]+'-'+start.getFullYear();
                            lastDay = new Date(start.getFullYear(), start.getMonth() + 1, 0);

                            if(lastDay > end) { lastDay = end; }

                            secondLast = new Date(lastDay.getFullYear(), lastDay.getMonth(), lastDay.getDate() - 1);
                            var secondLastDate = secondLast.getFullYear()+'-'+((secondLast.getMonth()+1)<=9?('0'+(secondLast.getMonth()+1)) : (secondLast.getMonth()+1))+'-'+(secondLast.getDate()<=9 ? '0'+secondLast.getDate() : secondLast.getDate());

                            while ($.inArray(secondLastDate, holidays) !== -1) {
                                secondLast = new Date(secondLast.getFullYear(), secondLast.getMonth(), secondLast.getDate() - 1);
                                secondLastDate = secondLast.getFullYear()+'-'+((secondLast.getMonth()+1)<=9?('0'+(secondLast.getMonth()+1)) : (secondLast.getMonth()+1))+'-'+(secondLast.getDate()<=9 ? '0'+secondLast.getDate() : secondLast.getDate());
                            }

                            $("#v-pills-tab").append('<li><a class="nav-link text-left" id="v-pills-'+month+'-tab" data-toggle="pill" href="#v-pills-'+month+'" role="tab" aria-controls="v-pills-'+month+'" aria-selected="false">'+month+'</a></li>');
                            html = '<div class="tab-pane fade" id="v-pills-'+month+'" role="tabpanel" aria-labelledby="v-pills-'+month+'-tab">'+
                                '       <div class="row" id="'+month+'-dates">'+
                                '           <div class="col-md-8">'+
                                '               <div class="input-group mb-3">'+
                                '                   <div class="input-group-prepend"><span class="input-group-text">'+dayArray[secondLast.getDay()]+' '+secondLast.getDate()+'-'+monthArray[secondLast.getMonth()]+'-'+secondLast.getFullYear()+'</span></div>'+
                                '                   <input type="number" class="form-control btn-square only-non-zero-number sub-allocation" name="sub-allocation['+secondLastDate+']" placeholder="Enter Sub-Allocation">'+
                                '               </div>'+
                                '          </div>'+
                                '       </div>';
                            $("#v-pills-tabContent").append(html);
                            start.setMonth( start.getMonth() + 1 );
                        }
                    }

                    $("#div_pacing_details").show();

                } else {
                    $(this).prop('checked', false);
                    alert('Select Start Date & End Date');
                }
            });

            $("#allocation").on('keyup', function () {
                var allocation = ($(this).val() > 0) ? $(this).val() : '0';
                $("#text-allocation").html(' / '+allocation);
            });
            $('body').on('keyup', ".sub-allocation",function () {
                var total = 0;

                $('body').find('.sub-allocation').each(function(){
                    if($(this).val() != '') {
                        total = total + parseInt($(this).val());
                    }
                });
                total = (total > 0) ? total : 0;
                $("#total-sub-allocation").html(total);

                if(total > parseInt($("#allocation").val())) {
                    $(this).val('');
                    $(this).keyup();
                }

            });
        });

        function getHtmlPacingDates(_this) {
            var month = $(_this).data('month');
            var year = $(_this).data('year');
            var selectedDays =  $(_this).val();

            var dayArr = $(_this).val();
            var allDates = [];

            $.each(dayArr, function () {
                $.merge(allDates, getDaysInMonthYear(parseInt(month), parseInt(year), parseInt(this)));
            });

            var html = '';
            $('body').find('#'+monthArray[month]+'-'+year+'-dates').html(html);
            $.each(allDates, function () {
                var currentDate = this.getFullYear()+'-'+((this.getMonth()+1)<=9?('0'+(this.getMonth()+1)) : (this.getMonth()+1))+'-'+(this.getDate()<=9 ? '0'+this.getDate() : this.getDate());
                var disabled = '';
                var place_holder = 'Sub-Allocation';
                var text_color = '';
                if($.inArray(currentDate, holidays) !== -1) {
                    disabled = ' disabled ';
                    place_holder = 'Holiday';
                    text_color = 'text-danger';
                }
                html = '<div class="col-md-6">'+
                    '               <div class="input-group mb-3">'+
                    '                   <div class="input-group-prepend"><span class="input-group-text '+text_color+'">'+dayArray[this.getDay()]+' '+this.getDate()+'-'+monthArray[this.getMonth()]+'-'+this.getFullYear()+'</span></div>'+
                    '                   <input type="number" class="form-control btn-square only-non-zero-number sub-allocation" name="sub-allocation['+currentDate+']" placeholder="'+place_holder+'" '+disabled+'>'+
                    '               </div>'+
                    '          </div>';
                $('body').find('#'+monthArray[month]+'-'+year+'-dates').append(html);
            });
        }

        function getDaysInMonthYear(month, year, weekday) {
            var date = new Date(year, month, 1);
            var days = [];
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var start = new Date(start_date);
            var end = new Date(end_date);

            while (date.getMonth() === month) {
                if(date.getDay() == weekday && (start <= date) && (end >= date)) {
                    days.push(new Date(date));
                }
                date.setDate(date.getDate() + 1);
            }
            return days;
        }
    </script>
@append
