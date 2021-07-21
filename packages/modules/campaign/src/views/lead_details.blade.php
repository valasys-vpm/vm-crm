<?php
    $totalAllocation = 0;
    $totalDeliverCount = 0;
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title h4" id="myLargeModalLabel">{{ $resultCampaign->name }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-12">
                    <h5 class="pb-1">Lead Details</h5>
                    <table class="table table-hover table-bordered text-center">
                        <tbody>
                        <tr class="table-primary">
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Deliver Count /<br>Allocation</th>
                            <th>Status</th>
                            <th>Pacing</th>
                            <th>Action</th>
                        </tr>
                        </tbody>
                        <tbody>
                        @forelse($resultCampaign->leadDetails as $leadDetail)
                            @php
                                $totalAllocation = $totalAllocation +  $leadDetail->allocation;
                                $totalDeliverCount = $totalDeliverCount +  $leadDetail->deliver_count;
                            @endphp
                        <tr class="table-light">
                            <td>{{ date('d/M/Y', strtotime($leadDetail->start_date)) }}</td>
                            <td>{{ date('d/M/Y', strtotime($leadDetail->end_date)) }}</td>
                            <td>{{ $leadDetail->deliver_count.' / '.$leadDetail->allocation }}</td>
                            <td>
                                @switch($leadDetail->campaign_status)
                                    @case(\Modules\Campaign\enum\CampaignStatus::LIVE)
                                    <span class="badge badge-pill badge-success" style="padding: 5px;width: 100px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$leadDetail->campaign_status] }}</span>
                                    @break
                                    @case(\Modules\Campaign\enum\CampaignStatus::PAUSED)
                                    <span class="badge badge-pill badge-warning" style="padding: 5px;width: 100px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$leadDetail->campaign_status] }}</span>
                                    @break
                                    @case(\Modules\Campaign\enum\CampaignStatus::CANCELLED)
                                    <span class="badge badge-pill badge-danger" style="padding: 5px;width: 100px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$leadDetail->campaign_status] }}</span>
                                    @break
                                    @case(\Modules\Campaign\enum\CampaignStatus::DELIVERED)
                                    <span class="badge badge-pill badge-primary" style="padding: 5px;width: 100px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$leadDetail->campaign_status] }}</span>
                                    @break
                                    @case(\Modules\Campaign\enum\CampaignStatus::REACTIVATED)
                                    <span class="badge badge-pill badge-success" style="padding: 5px;width: 100px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$leadDetail->campaign_status] }}</span>
                                    @break
                                @endswitch
                            </td>
                            <td>{{ $leadDetail->pacing }}</td>
                            <td>
                                <a onclick="editLeadDetail({{$leadDetail->id}})" href="javascript:void(0);" class="btn btn-outline-primary btn-rounded btn-sm" title="Edit"><i class="feather icon-edit mr-0"></i></a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @php $percentage = ($totalDeliverCount/$totalAllocation)*100; @endphp
            <div class="row" style="display: none;">
                <div class="col-md-12">
                    <h5 class="pb-1">Completion</h5>
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage}}%">{{$percentage}}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
