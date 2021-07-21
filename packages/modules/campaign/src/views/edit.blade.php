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
                                        <li class="breadcrumb-item"><a href="{{ route('campaign.show', base64_encode($resultCampaign->id)) }}">{{ $resultCampaign->campaign_id }}</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Edit Campaign</a></li>
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
                                            <h5>Edit Campaign</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-campaign-edit" method="post" action="{{ route('campaign.update', base64_encode($resultCampaign->id)) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="name">Campaign Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter campaign name" value="{{$resultCampaign->name}}">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="v_mail_campaign_id">V-Mail Campaign ID<span class="text-info"> <small>(Optional)</small></span></label>
                                                                <input type="text" class="form-control btn-square" id="v_mail_campaign_id" name="v_mail_campaign_id" placeholder="Enter v-mail campaign id" value="{{ $resultCampaign->v_mail_campaign_id }}">
                                                            </div>
                                                        </div>
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

                                                            <div class="col-md-12 form-group">
                                                                <label for="note">Note</label>
                                                                <textarea id="note" name="note" class="form-control classic-editor" placeholder="Enter note here..." rows="3">{{ $resultCampaign->note }}</textarea>
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
    <script src="{{ asset('public/template') }}/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
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
        $("#campaign_type_id").val('{{$resultCampaign->campaign_type_id}}');
        $("#campaign_filter_id").val('{{$resultCampaign->campaign_filter_id}}');
        $("#country_id").val(@json($resultCampaign->countries->pluck('country_id')->toArray()));
        $("#region_id").val(@json($resultCampaign->countries->pluck('country.region.id')->toArray()));
        $("#country_id").select2({
            placeholder: " -- Select Country(s) --",
        });
        $("#region_id").select2({
            placeholder: " -- Select Region(s) --",
        });
        $(function () {
            $("#form-campaign-edit").validate({
                ignore: [],
                focusInvalid: false,
                rules: {
                    'name' : { required : true },
                    'campaign_type_id' : { required : true },
                    'campaign_filter_id' : { required : true },
                    'country_id[]' : { required : true },
                    'v_mail_campaign_id' : {
                        required: false,
                        remote : {
                            url : '{{ route('campaign.validate.v_mail_campaign_id') }}',
                            data: { campaign_id : '{{ base64_encode($resultCampaign->id) }}' }
                        }
                    }
                },
                messages: {
                    'name' : { required : "Please enter campaign name" },
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
        });


    </script>
@append
