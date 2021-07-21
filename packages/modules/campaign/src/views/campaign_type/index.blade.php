@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
    <!-- toolbar css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/toolbar/css/jquery.toolbar.css')}}">
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
                                        <h5 class="m-b-10">Campaign Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('campaign') }}">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Type Management</a></li>
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
                                            <h5>Campaign Types</h5>
                                            <div class="float-right">
                                                @if(Helper::hasPermission('campaign_type.create'))<a href="{{ route('campaign_type.create') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New Campaign Type</button></a>@endif
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-campaign-types" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Abbreviation</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                        @if(Helper::hasPermission('campaign_type.edit') || Helper::hasPermission('campaign_type.destroy'))
                                                        <th style="width: 20%;text-align: center;">Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @forelse($resultCampaignTypes as $campaignType)
                                                        <tr>
                                                            <td>{{ $campaignType->full_name }}</td>
                                                            <td>{{ $campaignType->name }}</td>
                                                            <td>
                                                                @switch($campaignType->status)
                                                                    @case(\Modules\Campaign\enum\CampaignTypeStatus::ACTIVE)
                                                                    <span class="badge badge-pill badge-success">{{ \Modules\Campaign\enum\CampaignTypeStatus::CAMPAIGN_TYPE_STATUS[$campaignType->status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignTypeStatus::INACTIVE)
                                                                    <span class="badge badge-pill badge-danger">{{ \Modules\Campaign\enum\CampaignTypeStatus::CAMPAIGN_TYPE_STATUS[$campaignType->status] }}</span>
                                                                    @break
                                                                @endswitch
                                                            </td>

                                                            <td>{{ date('d M, Y \a\t h:i A', strtotime($campaignType->created_at)) }}</td>

                                                            @if(Helper::hasPermission('campaign_type.edit') || Helper::hasPermission('campaign_type.destroy'))
                                                            <td style="text-align: center;">
                                                                <div id="toolbar-options-{{$campaignType->id}}" class="hidden" style="border-radius: 0 !important;">
                                                                    @if(Helper::hasPermission('campaign_type.edit'))<a href="{{ route('campaign_type.edit', base64_encode($campaignType->id)) }}" onclick="javascript: location.href = '{{ route('campaign_type.edit', base64_encode($campaignType->id)) }}';" title="Edit" style="border-radius: 0;"><i class="feather icon-edit"></i></a>@endif
                                                                    @if(Helper::hasPermission('campaign_type.destroy'))<a href="{{ route('campaign_type.destroy', base64_encode($campaignType->id)) }}" title="Delete" onclick="javascript:if(confirm('Are you sure to delete campaign type?')){location.href = '{{ route('campaign_type.destroy', base64_encode($campaignType->id)) }}';};" style="border-radius: 0;"><i class="feather icon-trash-2"></i></a>@endif
                                                                </div>
                                                                <div data-toolbar="user-options" class="btn-toolbar btn-default btn-toolbar-light dark-toolbar d-inline-flex" id="dark-toolbar-{{$campaignType->id}}" data-id="{{$campaignType->id}}" title="More Action"><i class="feather icon-settings"></i></div>
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
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <script>
        $('#table-campaign-types').DataTable();
        // [ dark-toolbar ]
        $(function (){
            $('.dark-toolbar').each(function() {
                var id = $(this).data('id');
                $(this).toolbar({
                    content: '#toolbar-options-' + id,
                    position: 'left',
                    style: 'dark'
                });
            });
        });
    </script>
@append
