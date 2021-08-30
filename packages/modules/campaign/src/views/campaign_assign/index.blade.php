@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">
    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Assign</a></li>
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
                                    @if(Auth::user()->role_id != '31')
                                    <div class="card">
                                        <div class="card-header">
                                            @include('layouts.alert')
                                            <h5><i class="fas fa-list m-r-5"></i> Campaign Assign</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button style="display: none;" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <button type="button" class="btn minimize-card" id="filter-card-toggle"><i class="feather icon-plus"></i></button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" style="display: none;">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block" style="display: none;">
                                            <form id="form-campaign-user-assign">
                                                @csrf
                                                <input type="hidden" id="action-campaign-user-assign" name="action" value="{{ route('modal.campaign_user_assign') }}">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label for="campaign_status">Select Campaign(s)</label>
                                                        <select class="form-control btn-square p-1 pl-2 select2-multiple" id="campaign_list" name="campaign_list[]" style="height: unset;" multiple>
                                                            @foreach($resultCampaignsToAssign as $campaign)

                                                                <option id="campaign_list_{{ $campaign->id }}" value="{{ $campaign->id }}" data-name="{{ $campaign->name }}"  @if(isset($campaign->users[0]) && !empty($campaign->users[0]->allocation)) data-end-date="{{ $campaign->users[0]->display_date }}" data-allocation="{{ $campaign->users[0]->allocation }}" data-parent-id="{{ $campaign->users[0]->id }}" @else data-end-date="{{ $campaign->leadDetail->end_date }}" data-allocation="{{ $campaign->leadDetail->allocation }}" data-parent-id="" @endif>{{ $campaign->campaign_id.' - '.$campaign->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label for="campaign_status">Select User(s)</label>
                                                        <select class="form-control btn-square p-1 pl-2 select2-multiple" id="user_list" name="user_list[]" style="height: unset;" multiple>
                                                            @foreach($resultUsersToAssign as $user)
                                                                <option id="user_list_{{ $user->id }}" value="{{ $user->id }}" data-name="{{ $user->userDetail->first_name.' '.$user->userDetail->last_name }}">{{ $user->userDetail->first_name.' '.$user->userDetail->last_name.' - [ '.$user->role->name.' ]' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button id="button-filter-reset" type="reset" class="btn btn-outline-dark btn-square btn-sm"><i class="fas fa-undo m-r-5"></i>Reset</button>
                                                        <button id="button-campaign-user-assign" type="button" class="btn btn-outline-primary btn-square btn-sm"><i class="fas fa-filter m-r-5"></i>Apply</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign list</h5>
                                            <div class="card-header-right">

                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item"><a href="#!" id="reload-campaign-list"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block" style="padding-top: 10px;">
                                            <div class="row float-right" style="padding-bottom: 5px;">
                                                <div class="col-md-12">

                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table-campaigns" class="display table nowrap table-striped table-hover text-center" style="width: 100%;">
                                                    <thead class="text-center">
                                                    <tr>
                                                        <th>Campaign ID</th>
                                                        <th>Name</th>
                                                        @if(Auth::user()->role_id != '31')
                                                        <th>User(s)</th>
                                                        <th>Start Date</th>
                                                        @endif
                                                        <th>End Date</th>
                                                        <th>Allocation</th>
                                                        @if(Helper::hasPermission('campaign.edit') || Helper::hasPermission('campaign.show'))
                                                            <th>Action</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
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

    <div id="campaign-user-assign-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form id="form-campaign-user-assignment" method="post" action="{{ route('campaign_assign.store') }}">
                    @csrf
                    <input type="hidden" id="action-campaign-bulk-import" name="action" value="{{ route('modal.campaign_user_assign') }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Assign campaign to user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="">
                    <div class="modal-body">
                        <div id="div-campaign-user-assigned-preview">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-campaign-user-assign">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('javascript')
    @parent
    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- material datetimepicker Js -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <script>

        // [ dark-toolbar ]
        $(function (){
            var monthArray = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var CAMPAIGN_TABLE;

            CAMPAIGN_TABLE = $('#table-campaigns').DataTable({
                "lengthMenu": [ [500,400,300,200,100], [500,400,300,200,100] ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('campaign_assign.get_campaigns')}}",
                    data: {
                        filters: function (){
                            var obj = {
                                /*start_date: $("#start_date").val(),
                                end_date: $("#end_date").val(),
                                campaign_status: $("#campaign_status").val(),
                                delivery_day: $("#delivery_day").val(),
                                due_in: $("#due_in").val(),
                                past_date: $("#past_date").val(),
                                country_id: $("#country_id").val(),
                                region_id: $("#region_id").val(),
                                campaign_type_id: $("#campaign_type_id").val(),
                                campaign_filter_id: $("#campaign_filter_id").val()*/
                            };
                            localStorage.setItem("filters", JSON.stringify(obj));
                            return JSON.stringify(obj);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { checkSession(jqXHR); }
                },
                "columns": [
                    { data: 'campaign_id' },
                    /*{ data: 'name' },*/
                    {
                        render: function (data, type, row) {
                            var html = '';
                            var show_url = '{{ route('campaign.show') }}';
                            var id = btoa(row.id);
                            html += '<a href="'+show_url+'/'+id+'" class="text-dark double-click" title="View campaign details">'+row.name+'</a>';
                            return html;
                        }
                    },
                        @if(Auth::user()->role_id != '31')
                    {
                        render: function (data, type, row) {
                            var html = '';
                            return row.users.length;
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var date = new Date(row.lead_detail.start_date);
                            return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+monthArray[date.getMonth()]+'/'+date.getFullYear();
                        }
                    },
                        @endif
                        @if(Auth::user()->role_id == '34' || Auth::user()->role_id == '31')
                    {
                        render: function (data, type, row) {
                            var date = new Date(row.user.display_date);
                            return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+monthArray[date.getMonth()]+'/'+date.getFullYear();
                        }
                    },
                        @else
                    {
                        render: function (data, type, row) {
                            var date = new Date(row.lead_detail.end_date);
                            return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+monthArray[date.getMonth()]+'/'+date.getFullYear();
                        }
                    },
                        @endif
                    {
                        render: function (data, type, row) {
                            if(row.user == undefined) {
                                return row.lead_detail.allocation;
                            } else {
                                return row.user.allocation;
                            }
                            if(row.lead_detail.campaign_status == '{{\Modules\Campaign\enum\CampaignStatus::SHORTFALL}}') {
                                return row.lead_detail.deliver_count+' <span class="text-danger" title="Shortfall Count">('+row.lead_detail.shortfall_count+')</span>'+' / '+row.lead_detail.allocation;
                            } else {
                                return row.lead_detail.deliver_count+' / '+row.user.allocation;
                            }

                        }
                    },

                        @if(Helper::hasPermission('campaign.edit') || Helper::hasPermission('campaign.show'))
                    {
                        render: function (data, type, row) {
                            var html = '';
                            var show_url = '{{ route('campaign.show') }}';
                            var edit_url = '{{ route('campaign.edit') }}';
                            var id = btoa(row.id);
                            //html += '<a onclick="getLeadDetails('+row.id+')" href="javascript:void(0);" class="btn btn-outline-info btn-rounded btn-sm" title="View Lead Details"><i class="feather icon-eye mr-0"></i></a>';

                            html += '<a href="'+show_url+'/'+id+'" class="btn btn-outline-info btn-rounded btn-sm" title="View Lead Details"><i class="feather icon-eye mr-0"></i></a>';

                            @if(Helper::hasPermission('campaign.edit') && 0)
                                html += '<a href="'+edit_url+'/'+id+'" class="btn btn-outline-dark btn-rounded btn-sm" title="Edit Campaign Details"><i class="feather icon-edit mr-0"></i></a>';
                            @endif
                                return html;
                        }
                    },
                    @endif
                ],
                "fnDrawCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $('[data-toggle="tooltip"]').tooltip({
                        html: true
                    });
                    $('.double-click').doubleClickToGo();
                },
                "createdRow": function(row, data, dataIndex){
                    switch (data.lead_detail.campaign_status) {
                        case '1':
                            $(row).addClass('border-live');
                            break;
                        case '2':
                            $(row).addClass('border-paused');
                            break;
                    }
                    /*if( data[2] ==  `someVal`){
                        $(row).addClass('redClass');
                    }*/
                }
            });

            $("#filter-card-toggle").click(function (){
                if($(this).children('i').attr('class') === 'feather icon-minus') {
                    $(this).children('i').removeClass('icon-minus').addClass('icon-plus');
                } else {
                    $(this).children('i').removeClass('icon-plus').addClass('icon-minus');
                }
            });
            $('.dark-toolbar').each(function() {
                var id = $(this).data('id');
                $(this).toolbar({
                    content: '#toolbar-options-' + id,
                    position: 'left',
                    style: 'dark'
                });
            });

            $("#button-filter-reset").on('click', function(){
                $("#form-campaign-user-assign").find('input').val('');
                $("#form-campaign-user-assign").find('select').val('').trigger('change');
            });

            $('body').on('submit', '#form-campaign-user-assignment', function(event){
                event.preventDefault();
                var post_url = $(this).attr("action"); //get form action url
                var request_method = $(this).attr("method"); //get form GET/POST method
                var form_data = $(this).serialize();
                $.ajax({
                    type: request_method,
                    url: post_url,
                    data: form_data,
                    success: function (response) {
                        if(response.status === true) {
                            trigger_pnofify('success', 'Successful', response.message);
                        } else {
                            trigger_pnofify('error', 'Something went wrong', response.message);
                        }
                        $("#button-filter-reset").trigger('click');
                        $("#filter-card-toggle").trigger('click');
                        $("#campaign-user-assign-modal").modal('hide');
                        CAMPAIGN_TABLE.ajax.reload();             }
                });
            });

            $('body').on('click', '#button-campaign-user-assign', function(e){
                var campaign_list = $("#campaign_list").val();
                var user_list = $("#user_list").val();

                var html = '';

                $("#div-campaign-user-assigned-preview").html(html);

                $.each(campaign_list, function (key, value){
                    let display_date = new Date($("#campaign_list_"+value).data('end-date'));
                    display_date.setDate(display_date.getDate() - 2);
                    let d = new Date(display_date);
                    let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                    let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d);
                    let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
                    let tempDate = ye +'-'+ mo +'-'+da;
                    let yourDateObject = new Date(tempDate);
                    let dayOfWeek = yourDateObject.getDay();;
                    let isWeekend = (dayOfWeek === 6) || (dayOfWeek  === 0); // 6 = Saturday, 0 = Sunday
                    if (dayOfWeek === 6 || dayOfWeek  === 0 ) {
                        let d1 = new Date(tempDate);
                        d1.setDate(d1.getDate() - 1);
                        let d = new Date(d1);
                        let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                        let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d);
                        let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
                         tempDate = ye +'-'+ mo +'-'+da;
                    }
                    var cnt = user_list.length;
                    var divide = $("#campaign_list_"+value).data('allocation')/cnt;
                    var divide1 = $("#campaign_list_"+value).data('allocation')%cnt;

                    html += '<div class="card border border-info rounded">' +
                        '                            <h5 class="card-header" style="padding: 10px 25px;">'+$("#campaign_list_"+value).data('name')+'</h5>' +
                                                        '<input type="hidden" name="data['+key+'][campaign_id]" value="'+value+'">' +
                                                        '<input type="hidden" name="data['+key+'][parent_id]" value="'+$("#campaign_list_"+value).data('parent-id')+'">' +
                        '                            <div class="card-body" style="padding: 15px 25px;">' +
                        '                                <div class="row">' +
                        '                                    <div class="col-md-5">' +
                        '                                        <div class="row">' +
                        '                                            <div class="col-md-6"><h6 class="card-title">Allocation</h6></div>' +
                        '                                            <div class="col-md-6"><h6 class="card-title">: '+$("#campaign_list_"+value).data('allocation')+'</h6></div>' +
                        '                                        </div>' +
                        @if(Auth::user()->role_id == '34')
                        '                                        <div class="row">' +
                        '                                            <div class="col-md-6"><h6 class="card-title">End Date</h6></div>' +
                        '                                            <div class="col-md-6"><h6 class="card-title">: '+$("#campaign_list_"+value).data('end-date')+'</h6></div>' +
                        '                                        </div>' +
                        @else
                        '                                        <div class="row">' +
                        '                                            <div class="col-md-6"><h6 class="card-title">End Date</h6></div>' +
                        '                                            <div class="col-md-6"><h6 class="card-title">: '+$("#campaign_list_"+value).data('end-date')+'</h6></div>' +
                        '                                        </div>' +
                        '                                        <div class="row">' +
                        '                                            <div class="col-md-6"><h6 class="card-title">Display Date</h6></div>' +
                        '                                            <div class="col-md-6"><h6 class="card-title">: <input type="date" name="data['+key+'][display_date]" placeholder="DD/MMM/YYY" value="'+tempDate+'"> </h6></div>' +
                        '                                        </div>' +
                        @endif
                        '                                    </div>' +
                        '                                    <div class="col-md-7 border-left">' +
                        '                                        <h5 class="card-title mb-2">User(s) to Assign</h5>' +
                        '                                        <hr class="m-0" style="margin-bottom: 5px !important;">' +
                        '                                        <div>';

                    $.each(user_list, function (key2, user_id){
                        if (key2 === (user_list.length - 1)) {divide = (divide + divide1);}
                        html += '<div class="row p-1">' +
                            '                                                <div class="col-md-5"><h6 class="card-title">'+$("#user_list_"+user_id).data('name')+'</h6></div>' +
                                                                            '<input type="hidden" name="data['+key+'][users]['+key2+'][user_id]" value="'+user_id+'">' +
                            '                                                <div class="col-md-7">' +
                            '                                                    <input type="text" name="data['+key+'][users]['+key2+'][allocation]" class="form-control form-control-sm" value="'+Math.floor(divide)+'" style="height: 30px;">' +
                            '                                                </div>' +
                            '                                            </div>';
                    });

                    html += '                                    </div>' +
                        '                                    </div>' +
                        '                                </div>' +
                        '                            </div>' +
                        '                        </div>';
                });

                $("#div-campaign-user-assigned-preview").append(html);

                $("#campaign-user-assign-modal").modal('show');
            });

        });
    </script>
@append
