<?php
    $totalAllocation = 0;
    $totalDeliverCount = 0;
?>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="myLargeModalLabel">Edit Sub Allocations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-12">
                    <form id="form-edit-lead-details" method="post" action="{{ route('campaign.update_sub_allocations', base64_encode($resultLeadDetail->campaign_id)) }}">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ base64_encode($resultLeadDetail->id) }}">
                        <input type="hidden" id="txt_allocation" value="{{ $resultLeadDetail->allocation }}">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="start_date">Start Date: {{ date('d-M-Y', strtotime($resultLeadDetail->start_date)) }}</label>
                                <input type="hidden" id="txt_start_date" value="{{ date('d/M/Y', strtotime($resultLeadDetail->start_date)) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="end_date">End Date: {{ date('d-M-Y', strtotime($resultLeadDetail->end_date)) }}</label>
                                <input type="hidden" id="txt_end_date" value="{{ date('d/M/Y', strtotime($resultLeadDetail->end_date)) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="pacing">Pacing</label>
                                <div class="form-control">
                                    <div class="form-group d-inline">
                                        <div class="radio radio-primary d-inline">
                                            <input type="radio" name="pacing" id="pacing_radio_1" value="Daily" class="pacing" @if($resultLeadDetail->pacing == 'Daily') checked @endif disabled>
                                            <label for="pacing_radio_1" class="cr">Daily</label>
                                        </div>
                                    </div>
                                    <div class="form-group d-inline">
                                        <div class="radio radio-primary d-inline">
                                            <input type="radio" name="pacing" id="pacing_radio_2" value="Monthly" class="pacing" @if($resultLeadDetail->pacing == 'Monthly') checked @endif disabled>
                                            <label for="pacing_radio_2" class="cr">Monthly</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="total-sub-allocation">Total Sub-Allocation</label>
                                <br>
                                <span id="total-sub-allocation" class="h3">{{ $total_sub_allocation }}</span><span id="text-allocation" class="h3"> / {{ $resultLeadDetail->allocation }}</span>
                            </div>
                            <div class="col-md-12 row" id="div_pacing_details">
                                <div class="col-md-3 col-sm-12">
                                    <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        @forelse($monthList as $key => $month)
                                        <li>
                                            <a class="nav-link text-left @if($key == 0) show active @endif" id="v-pills-{{$month['name']}}-tab" data-toggle="pill" href="#v-pills-{{$month['name']}}" role="tab" aria-controls="v-pills-{{$month['name']}}" aria-selected="false">{{$month['name']}}</a>
                                        </li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-9 col-sm-12">
                                    <div class="tab-content" id="v-pills-tabContent">

                                        @forelse($monthList as $key => $month)
                                            <div class="tab-pane fade @if($key == 0) show active @endif" id="v-pills-{{$month['name']}}" role="tabpanel" aria-labelledby="v-pills-{{$month['name']}}-tab">
                                                @if($resultLeadDetail->pacing == 'Daily')
                                                    @if(isset($month['days']) && !empty($month['days']))
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label for="days">Select Day(s)<span class="text-danger">*</span></label>
                                                            <select class="form-control btn-square select2-multiple select2-multiple-days" id="{{$month['name']}}_days" name="days[{{$month['name']}}][]" multiple="multiple" data-month="{{$month['month']-1}}" data-year="{{$month['year']}}" onChange="getHtmlPacingDates(this);">
                                                                <option value="1" @if(in_array('1', $month['days'])) selected @endif> Monday</option>
                                                                <option value="2" @if(in_array('2', $month['days'])) selected @endif> Tuesday</option>
                                                                <option value="3" @if(in_array('3', $month['days'])) selected @endif> Wednesday</option>
                                                                <option value="4" @if(in_array('4', $month['days'])) selected @endif> Thursday</option>
                                                                <option value="5" @if(in_array('5', $month['days'])) selected @endif> Friday</option>
                                                                <option value="6" @if(in_array('6', $month['days'])) selected @endif> Saturday</option>
                                                                <option value="0" @if(in_array('0', $month['days'])) selected @endif> Sunday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="days">Select Day(s)<span class="text-danger">*</span></label>
                                                                <select class="form-control btn-square select2-multiple select2-multiple-days" id="{{$month['name']}}_days" name="days[{{$month['name']}}][]" multiple="multiple" data-month="{{$month['month']-1}}" data-year="{{$month['year']}}" onChange="getHtmlPacingDates(this);">
                                                                    <option value="1"> Monday</option>
                                                                    <option value="2"> Tuesday</option>
                                                                    <option value="3"> Wednesday</option>
                                                                    <option value="4"> Thursday</option>
                                                                    <option value="5"> Friday</option>
                                                                    <option value="6"> Saturday</option>
                                                                    <option value="0"> Sunday</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="row" id="{{$month['name']}}-dates">
                                                    @forelse($month['pacing_details'] as $key => $pacingDetail)
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend"><span class="input-group-text @if($pacingDetail['is_holiday']) text-danger @endif">{{ $pacingDetail['title'] }}</span></div>
                                                            <input type="number" class="form-control btn-square only-non-zero-number sub-allocation" name="sub-allocation[{{$key}}]" value="{{ $pacingDetail['sub_allocation'] }}"  @if($pacingDetail['is_holiday']) disabled placeholder="Holiday" @else placeholder="Enter Sub-Allocation" @endif>
                                                        </div>
                                                    </div>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
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
