@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- footable css -->
    <link rel="stylesheet" href="{{ asset('public/template/') }}/assets/plugins/footable/css/footable.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/template/') }}/assets/plugins/footable/css/footable.standalone.min.css">

    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">

    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css">

    <style>
        .modal {
            z-index: 99999999 !important;
        }
        .dtp{z-index:999999999 !important;}
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
                                        <h5 class="m-b-10">Campaign Management</h5>
                                        <div class="card-header-right mb-1" style="float: right;">
                                            <a href="{{ route('campaign') }}" class="btn btn-outline-dark btn-square btn-sm" style="font-weight: bold;"><i class="feather icon-arrow-left"></i>Back</a>
                                        </div>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('campaign') }}">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Details</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="col-md-12">
                                @include('layouts.alert')
                            </div>
                            <div class="row">
                                <!-- [ task-detail ] start -->
                                <div class="col-xl-4 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col">
                                                    <h5><span class="text-muted">ID: </span>{{ $resultCampaign->campaign_id }}</h5>
                                                    <h6><span><span class="text-muted">Name: </span>{{ $resultCampaign->name }}</span></h6>
                                                    @if($resultCampaign->v_mail_campaign_id)
                                                        <h6><span><span class="text-muted">V-Mail Campaign ID: </span>{{ $resultCampaign->v_mail_campaign_id }}</span></h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign Details</h5>
                                            <div class="card-header-right">
                                                @if(Helper::hasPermission('campaign.edit'))
                                                    <a href="{{ route('campaign.edit', base64_encode($resultCampaign->id)) }}" class="btn btn-outline-primary btn-sm btn-square"><i class="feather icon-edit mr-0"></i> Edit</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <h6 class="text-muted f-w-300">Campaign Type: <span class="float-right">{{ $resultCampaign->campaignType->name }}</span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Campaign Filter: <span class="float-right">{{ $resultCampaign->campaignFilter->name }}</span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Country(s): <br><br><span class="float-right">
                                                    @foreach($resultCampaign->countries->pluck('country.name')->toArray() as $country)
                                                        <span class="badge badge-info m-1" style="padding: 5px 15px;">{{$country}}</span>
                                                    @endforeach
                                                </span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Region(s): <br><br><span class="float-right">
                                                    @foreach($resultCampaign->countries->pluck('country.region.name')->unique()->toArray() as $region)
                                                        <span class="badge badge-dark m-1" style="padding: 5px 15px;">{{$region}}</span>
                                                    @endforeach
                                                </span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">
                                                Note: <br><br>
                                                <span class="float-right">
                                                    @if(strlen($resultCampaign->note) > 200)
                                                        <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#modal-campaign-note">View Note</button>
                                                    @else
                                                        {!! $resultCampaign->note !!}
                                                    @endif
                                                </span>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Specifications</h5>
                                            <div class="card-header-right">
                                                @if(Helper::hasPermission('campaign.attach_specification'))
                                                <button class="btn btn-primary btn-sm btn-square pt-1 pb-1" data-toggle="modal" data-target="#modal-attach-specification" style=""><i class="feather icon-plus mr-0"></i> Attach</button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-block task-attachment">
                                            <ul class="media-list p-0" id="specification_ul">
                                                @forelse($resultCampaign->specifications as $specification)
                                                <li class="media d-flex m-b-15 specification-li">
                                                    <div class="m-r-20 file-attach">
                                                        <i class="far fa-file f-28 text-muted"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <a href="{{ url('public/storage/campaigns/'.$resultCampaign->campaign_id.'/'.$specification->file_name) }}" target="_blank" download data-toggle="tooltip" data-placement="top" data-original-title="Click to view"><span class="m-b-5 d-block text-primary">{{$specification->file_name}}</span></a>
                                                    </div>
                                                    @if(Helper::hasPermission('campaign.remove_specification'))
                                                    <div class="float-right text-muted">
                                                        <a href="#!" onclick="removeSpecification(this, '{{base64_encode($specification->id)}}');"><i class="fas fa-times f-24 text-danger"></i></a>
                                                    </div>
                                                    @endif
                                                </li>
                                                @empty
                                                <li class="media d-flex m-b-15">
                                                    <div class="media-body">
                                                        <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No File Attached</a>
                                                    </div>
                                                </li>
                                                @endforelse

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-lg-12">

                                    <div class="card">
                                        <div class="card-header">

                                            <h5><i class="fas fa-chart-pie m-r-5"></i> Lead Details</h5>

                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    @if(Helper::hasPermission('campaign.create_new_lead'))

                                                        <span>
                                                            <button type="button" onclick="location.href='{{ url('campaign/export') }}/{{ base64_encode($resultCampaign->id) }}'" class="btn btn-primary btn-sm btn-square pt-1 pb-1"><i class="feather icon-download"></i>Export</button>
                                                            <a href="{{ route('campaign.create_new_lead', ['campaign_id' => $resultCampaign->campaign_id, 'id' => base64_encode($resultCampaign->id)]) }}">
                                                                <button class="btn btn-primary btn-sm btn-square pt-1 pb-1"><i class="feather icon-plus"></i>New Lead</button>
                                                            </a>
                                                        </span>
                                                    @endif
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block">

                                            <div class="m-b-20">
                                                <div class="table-responsive">
                                                    <table class="table m-b-0 f-14 b-solid requid-table">
                                                        <thead>
                                                        <tr class="text-uppercase">
                                                            @if(Helper::hasPermission('campaign.view_sub_allocations'))
                                                            <th class="text-center">#</th>
                                                            @endif
                                                            <th class="text-center">Start Date</th>
                                                            <th class="text-center">End Date</th>
                                                            <th class="text-center">Completion</th>
                                                            <th class="text-center">Deliver Count / <br>Allocation</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-center text-muted">
                                                        @forelse($resultCampaign->leadDetails as $lead)
                                                            <tr>
                                                                @if(Helper::hasPermission('campaign.view_sub_allocations'))
                                                                <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>
                                                                @endif
                                                                <td>{{ date('d-M-Y', strtotime($lead->start_date)) }}</td>
                                                                <td>{{ date('d-M-Y', strtotime($lead->end_date)) }}</td>
                                                                <td>
                                                                    @php
                                                                        $percentage = ($lead->deliver_count/$lead->allocation)*100;
                                                                        $percentage = number_format($percentage,2,".", "");
                                                                        if($percentage == 100) {
                                                                            $color_class = 'bg-success';
                                                                        } else {
                                                                            $color_class = 'bg-warning text-dark';
                                                                        }
                                                                    @endphp
                                                                    <div class="progress mb-4" style="height: 20px;border: 1px solid #e2dada;">
                                                                        <div class="progress-bar {{ $color_class }}" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage}}%; font-weight: bolder;">{{$percentage}}%</div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if($lead->campaign_status == \Modules\Campaign\enum\CampaignStatus::SHORTFALL)
                                                                        {{ $lead->deliver_count }} <span class="text-danger" title="Shortfall Count">({{ $lead->shortfall_count }})</span> / {{ $lead->allocation }}
                                                                    @else
                                                                        {{ $lead->deliver_count.' / '.$lead->allocation }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @switch($lead->campaign_status)
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::LIVE)
                                                                        <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::PAUSED)
                                                                        <span class="badge badge-pill badge-warning" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::CANCELLED)
                                                                        <span class="badge badge-pill badge-danger" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::DELIVERED)
                                                                        <span class="badge badge-pill badge-primary" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::REACTIVATED)
                                                                        <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                        @case(\Modules\Campaign\enum\CampaignStatus::SHORTFALL)
                                                                        <span class="badge badge-pill badge-secondary" style="padding: 5px;min-width: 80px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                        @break
                                                                    @endswitch
                                                                </td>
                                                            </tr>
                                                            @if(Helper::hasPermission('campaign.view_sub_allocations'))
                                                            <tr class="pacing-details" style="display: none;">
                                                                <td colspan="6" class="bg-light text-left">
                                                                    <h6>
                                                                        <span class="text-muted">Pacing: </span>{{ $lead->pacing }}
                                                                        @if(Helper::hasPermission('campaign.update_sub_allocations'))
                                                                        <span class="float-right btn btn-outline-dark btn-sm btn-square pt-1 pb-1" data-toggle="modal" onclick="editSubAllocations('{{$lead->id}}');">Update Sub-Allocations</span>
                                                                        @endif
                                                                        @if(Helper::hasPermission('campaign.update_lead_details'))
                                                                        <span class="float-right btn btn-outline-primary btn-sm btn-square pt-1 pb-1" data-toggle="modal" onclick="editLeadDetails('{{$lead->id}}','{{$lead->start_date}}','{{$lead->end_date}}','{{$lead->deliver_count}}', '{{$lead->allocation}}', '{{$lead->campaign_status}}', '{{$lead->shortfall_count}}');">Edit Lead Details</span>
                                                                        @endif
                                                                    </h6>
                                                                    <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                                                    <table class="table table-hover foo-table text-center">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="text-center" data-breakpoints="xs">Date</th>
                                                                            <th class="text-center" data-breakpoints="xs">Day</th>
                                                                            <th class="text-center" data-breakpoints="xs">Sub-Allocation</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @forelse($lead->pacingDetails as $subAllocation)
                                                                            @if($subAllocation->sub_allocation)
                                                                            <tr>
                                                                                <td>{{ date('d-M-Y', strtotime($subAllocation->date)) }}</td>
                                                                                <td>{{ date('D', strtotime($subAllocation->date)) }}</td>
                                                                                <td>{{ $subAllocation->sub_allocation }}</td>
                                                                            </tr>
                                                                            @endif
                                                                        @empty
                                                                        @endforelse
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @empty
                                                        @endforelse

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(Helper::hasPermission('campaign.get_campaign_history'))
                                    <div class="card" id="card-campaign-history" style="overflow-y: auto;">
                                        <div class="card-header">
                                            <h5><i class="fas fa-clock m-r-5"></i> Campaign History</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item reload-card"><a href="#!" id="reload-campaign-history"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <ul class="task-list" id="campaign-history-ul">

                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-center">
                                                <button id="btn-get-campaign-history" type="button" class="btn btn-warning shadow-4 btn-sm text-dark btn-square pt-1 pb-1" onclick="getCampaignHistory(this);"><i class="fas fa-spinner"></i> Load More</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                <!-- [ task-detail ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-campaign-note" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Campaign Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="font-size: 17px;">
                        {!! $resultCampaign->note !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-attach-specification" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attach Specification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form id="form-attach-specification" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="specifications">Specifications</label>
                                    <input type="file" class="form-control-file" id="specifications" name="specifications[]" multiple required>
                                </div>
                            </div>
                            <button type="reset" class="btn btn-secondary btn-square float-right">Clear</button>
                            <button type="submit" class="btn btn-primary btn-square float-right">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit-lead-details" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Edit Lead Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="form-edit-lead-details" method="post" action="{{ route('campaign.update_lead_details', base64_encode($resultCampaign->id)) }}">
                                @csrf
                                <input type="hidden" id="txt_lead_id" name="lead_id" value="">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="start_date_1">Start Date<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control btn-square" id="start_date_1" name="start_date" placeholder="Select Start Date">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="end_date_1">End Date<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control btn-square" id="end_date_1" name="end_date" placeholder="Select End Date">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="allocation">Allocation<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control btn-square only-non-zero-number" id="allocation" name="allocation" placeholder="Enter allocation">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="deliver_count">Deliver Count<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control btn-square only-non-zero-number" id="deliver_count" name="deliver_count" placeholder="Enter Deliver Count">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="campaign_status">Status</label>
                                        <select class="form-control btn-square" id="campaign_status" name="campaign_status">
                                            @foreach(\Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS as $campaign_status => $value)
                                                <option value="{{ $campaign_status }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="div-shortfall-count" class="col-md-6 form-group" style="display: none;">
                                        <label for="shortfall_count">Shortfall Count<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control btn-square only-non-zero-number" id="shortfall_count" name="shortfall_count" placeholder="Enter Shortfall Count" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning" role="alert">
                                            <p>Warning: If Start Date or End Date are updated then corresponding sub-allocation will be updated or removed.</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-square float-right">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit-sub-allocations" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;" >

    </div>

@endsection

@section('javascript')
    @parent
    <!-- footable Js -->
    <script src="{{ asset('public/template/assets/plugins/footable/js/footable.min.js') }}"></script>

    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- material datetimepicker Js -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="{{ asset('public/template') }}/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script>
        var CAMPAIGN_HISTORY_SKIP = 0;

        var holidays = @json($resultHolidays);

        $(function(){

            $('#start_date_1').bootstrapMaterialDatePicker({
                weekStart: 0,
                time: false,
                format: 'D-MMM-YYYY',
                dropdownParent: $('#modal-edit-lead-details'),
                switchOnClick : true,
            }).on('change', function(e, date) {
                $('#end_date_1').bootstrapMaterialDatePicker('setMinDate', date);
            });

            $('#end_date_1').bootstrapMaterialDatePicker({
                weekStart: 0,
                time: false,
                format: 'D-MMM-YYYY',
                dropdownParent: $('#modal-edit-lead-details'),
                switchOnClick : true,
            });

            $('.foo-table').footable({
                "paging": {
                    "enabled": true
                },
                /*"sorting": {
                    "enabled": true
                }*/
            });

            $('.toggle-pacing-details').click(function (){
                //$('.pacing-details').hide();
                //$('.toggle-pacing-details').removeClass('icon-minus-square').addClass('icon-plus-square');;
                if($(this).hasClass('icon-plus-square')) {
                    $(this).removeClass('icon-plus-square').addClass('icon-minus-square');
                    $(this).parents('tr').next('tr').show(1000);
                } else {
                    $(this).removeClass('icon-minus-square').addClass('icon-plus-square');
                    $(this).parents('tr').next('tr').hide(500);
                }
            });

            @if(Helper::hasPermission('campaign.attach_specification'))
            $("#form-attach-specification").submit(function (){
                var form_data = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ route('campaign.attach_specification', base64_encode($resultCampaign->id)) }}',
                    processData: false,
                    contentType: false,
                    data: form_data,
                    type: 'post',
                    success: function(response) {
                        console.log(response);
                        if(response.status == 'true') {
                            if($('.specification-li').length == 0) {
                                $('#specification_ul').html('');
                            }
                            $.each(response.data, function(){
                                var html = '<li class="media d-flex m-b-15 specification-li">\n\
                                                <div class="m-r-20 file-attach">\n\
                                                    <i class="far fa-file f-28 text-muted"></i>\n\
                                                </div>\n\
                                                <div class="media-body">\n\
                                                    <a href="{{ url('public/storage/campaigns/'.$resultCampaign->campaign_id) }}/'+this.file_name+'" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Click to view"><span class="m-b-5 d-block text-primary">'+this.file_name+'</span></a>\n\
                                                </div>\n\
                                                <div class="float-right text-muted">\n\
                                                    <a href="#!" onclick="removeSpecification(this, \''+btoa(this.id)+'\');"><i class="fas fa-times f-24 text-danger"></i></a>\n\
                                                </div>\n\
                                            </li>';
                                $('#specification_ul').append(html);
                            });
                            $("#reload-campaign-history").click();
                        } else {
                            alert('Something went wrong, please try again.');
                        }
                        $("#modal-attach-specification").modal('hide');
                    }
                });
                return false;
            });
            @endif

            $("#reload-campaign-history").on('click', function (){
                CAMPAIGN_HISTORY_SKIP = 0;
                $("#btn-get-campaign-history").click();
            });

            $("#campaign_status").change(function (){
                if($(this).val() == '{{\Modules\Campaign\enum\CampaignStatus::SHORTFALL}}') {
                    $("#div-shortfall-count").show();
                    $("#shortfall_count").removeAttr('disabled');
                } else {
                    $("#div-shortfall-count").hide();
                    $("#shortfall_count").attr('disabled','disabled');
                }
            });

            initFunction();
        });

        function editLeadDetails(lead_id,start_date, end_date, deliver_count, allocation, campaign_status, shortfall_count) {
            $('#start_date_1').bootstrapMaterialDatePicker('setDate', new Date(start_date));
            $('#end_date_1').bootstrapMaterialDatePicker('setMinDate', new Date(start_date));
            $('#end_date_1').bootstrapMaterialDatePicker('setDate', new Date(end_date));
            $('#txt_lead_id').val(btoa(lead_id));
            $('#allocation').val(allocation);
            $('#deliver_count').val(deliver_count);
            $('#campaign_status').val(campaign_status);
            if(campaign_status == '{{\Modules\Campaign\enum\CampaignStatus::SHORTFALL}}') {
                $("#div-shortfall-count").show();
                $("#shortfall_count").removeAttr('disabled');
                $('#shortfall_count').val(shortfall_count);
            } else {
                $("#div-shortfall-count").hide();
                $("#shortfall_count").attr('disabled','disabled');
                $('#shortfall_count').val('');
            }
            $("#modal-edit-lead-details").modal('show');
        }

        function editSubAllocations(lead_id) {
            $.ajax({
                url: "{{route('campaign.get_sub_allocations')}}"+"/"+btoa(lead_id),
                success: function (data){
                    $("#modal-edit-sub-allocations").html(data);
                    $(".select2-multiple-days").select2({
                        placeholder: " -- Select Day(s) --",
                        dropdownParent: $('#modal-edit-sub-allocations')
                    });
                    $("#modal-edit-sub-allocations").modal('show');
                }
            });
        }

        @if(Helper::hasPermission('campaign.remove_specification'))
        function removeSpecification(_this, specification_id) {
            if(confirm("Are you sure to remove specification?")) {
                $.ajax({
                    url: "{{route('campaign.remove_specification')}}"+"/"+specification_id,
                    success: function (response){
                        if(response == 'true') {
                            $(_this).parents('.specification-li').remove();
                            //alert('Specification deleted successfully.');
                            if($('.specification-li').length == 0) {
                                var html = '<li class="media d-flex m-b-15"> <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No File Attached</a> </div> </li>';
                                $('#specification_ul').html(html);
                            }

                            $("#reload-campaign-history").click();
                        } else {
                            alert('Unable to remove specification, please try again.');
                        }
                    }
                });
            }
        }
        @endif

        @if(Helper::hasPermission('campaign.get_campaign_history'))
        function getCampaignHistory(button) {
            $.ajax({
                url: '{{ route('campaign.get_campaign_history', base64_encode($resultCampaign->id)) }}',
                data: { skip:CAMPAIGN_HISTORY_SKIP },
                success: function(response){
                    if(CAMPAIGN_HISTORY_SKIP == 0) {
                        $("#campaign-history-ul").html('');
                    }
                    $("#campaign-history-ul").append(response);
                    CAMPAIGN_HISTORY_SKIP++;
                },
                complete: function () {
                    $(button).find('i').removeClass('fa-spin');
                    $("#card-campaign-history").removeClass('card-load');
                    $("#card-campaign-history").find('.card-loader').remove();
                },
                beforeSend: function(){
                    $(button).find('i').addClass('fa-spin');
                    $("#card-campaign-history").addClass('card-load');
                    $("#card-campaign-history").append('<div class="card-loader"><i class="pct-loader1 anim-rotate"></i></div>');

                },
                error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
            });
        }
        @endif

        function initFunction() {
            $("#btn-get-campaign-history").click();
        }

    </script>
    <script src="{{asset('public/js/pacing.js?='.time()) }}"></script>
@append
