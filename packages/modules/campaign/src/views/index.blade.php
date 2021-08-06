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

    <style>
        .table tbody tr:hover {
            -webkit-box-shadow: 0 5px 8px -6px grey;
            -moz-box-shadow: 0 5px 8px -6px grey;
            box-shadow: 0 5px 8px -6px grey;
        }
        .table td {
            padding: 5px 5px 0px 5px !important;
            vertical-align: middle !important;
            border-top: 2px solid #dad9d9 !important;
            border-bottom: 2px solid #dad9d9 !important;
        }
        .table tr td:first-child {
            border: 2px solid #dad9d9 !important;
            border-right: 0px solid transparent !important;
            border-radius: 8px 0 0 8px;
        }
        .table tr td:last-child {
            border: 2px solid #dad9d9 !important;
            border-left: 0px solid transparent !important;
            border-radius: 0px 8px 8px 0;
        }

        table.dataTable {
            border-spacing: 0px 10px !important;
        }

        /*Border Live*/
        .table tr.border-live {
            background-color: rgba(226,239,219,0.35) !important;
        }
        .table tr.border-live td {
            border-color: #92D050 !important;
        }
        .table tr.border-live td:first-child {
            border-right: 0px solid transparent !important;
        }
        .table tr.border-live td:last-child {
            border-left: 0px solid transparent !important;
        }

        /*Border Paused*/
        .table tr.border-paused {
            background-color: rgba(255,230,153,0.25) !important;
        }
        .table tr.border-paused td {
            border-color: #fbcb39 !important;
        }
        .table tr.border-paused td:first-child {
            border-right: 0px solid transparent !important;
        }
        .table tr.border-paused td:last-child {
            border-left: 0px solid transparent !important;
        }

    </style>
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Management</a></li>
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
                                            <h5><i class="fas fa-filter m-r-5"></i> Campaign Filters</h5>
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
                                            <form id="form-campaign-filters">
                                                <div class="row">
                                                    <div class="col-md-2 form-group">
                                                        <label for="start_date">Start Date</label>
                                                        <input type="text" class="form-control btn-square p-1 pl-2" id="start_date" name="start_date" placeholder="Select Start Date">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="end_date">End Date</label>
                                                        <input type="text" class="form-control btn-square p-1 pl-2" id="end_date" name="end_date" placeholder="Select End Date">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="campaign_status">Status</label>
                                                        <select class="form-control btn-square p-1 pl-2 select2-multiple" id="campaign_status" name="campaign_status[]" style="height: unset;" multiple>
                                                            @foreach(\Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS as $campaign_status => $value)
                                                                <option value="{{ $campaign_status }}">{{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="delivery_day">Delivery Day</label>
                                                        <select class="form-control btn-square select2-multiple" id="delivery_day" name="delivery_day[]" style="height: unset;" multiple="multiple">
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
                                                        <select class="form-control btn-square p-1 pl-2" id="due_in" name="due_in" style="height: unset;">
                                                            <option value=""> -- Select -- </option>
                                                            <option value="Today">Today</option>
                                                            <option value="Tomorrow">Tomorrow</option>
                                                            <option value="7 Days">7 Days</option>
                                                            <option value="Past Due">Past Due</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="country_id">Country(s)</label>
                                                        <select class="form-control btn-square select2-multiple" id="country_id" name="country_id[]" multiple="multiple" style="height: unset;">
                                                            @foreach($resultCountries as $country)
                                                                <option value="{{$country->id}}" data-region-id="{{$country->region_id}}">{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="region_id">Region(s)</label>
                                                        <select class="form-control btn-square select2-multiple" id="region_id" name="region_id[]" multiple="multiple" style="height: unset;">
                                                            @foreach($resultRegions as $region)
                                                                <option value="{{$region->id}}" data-abbreviation="{{ $region->abbreviation }}">{{ $region->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="campaign_type_id">Campaign Type</label>
                                                        <select class="form-control btn-square p-1 pl-2" id="campaign_type_id" name="campaign_type_id"  style="height: unset;">
                                                            <option value="">-- Select Campaign Type --</option>
                                                            @foreach($resultCampaignTypes as $campaign_type)
                                                                <option value="{{$campaign_type->id}}">{{ $campaign_type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <label for="campaign_filter_id">Campaign Filter</label>
                                                        <select class="form-control btn-square p-1 pl-2" id="campaign_filter_id" name="campaign_filter_id"  style="height: unset;">
                                                            <option value="">-- Select Campaign Filter --</option>
                                                            @foreach($resultCampaignFilters as $campaign_filter)
                                                                <option value="{{$campaign_filter->id}}">{{ $campaign_filter->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button id="button-filter-reset" type="reset" class="btn btn-outline-dark btn-square btn-sm"><i class="fas fa-undo m-r-5"></i>Reset</button>
                                                        <button id="button-filter-apply" type="button" class="btn btn-outline-primary btn-square btn-sm"><i class="fas fa-filter m-r-5"></i>Apply</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign list</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <a onclick="getModal('{{ array_slice(explode('/', route('modal.campaign.import')), -1, 1)[0] }}')" data-toggle="modal"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-upload"></i>Import</button></a>&nbsp;&nbsp;
                                                    <a href="{{ url('campaign/export') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-download"></i>Export</button></a>&nbsp;&nbsp;
                                                    @if(Helper::hasPermission('campaign.create'))<a href="{{ route('campaign.create') }}"><button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New Campaign</button></a>@endif

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
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-campaigns" class="display table nowrap table-striped table-hover text-center" style="width: 100%;">
                                                    <thead class="text-center">
                                                    <tr>
                                                        <th>Campaign ID</th>
                                                        <th>Name</th>
                                                        <th>Completion</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Deliver Count /<br> Allocation</th>
                                                        <th>Status</th>
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
        var monthArray = ['Jan','Feb','Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var dayArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var regions = [];
        var current_date = new Date();
        var CAMPAIGN_TABLE;

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
        //$('#start_date').bootstrapMaterialDatePicker('setDate', new Date(current_date.getFullYear(), current_date.getMonth(), 1));
        //$('#end_date').bootstrapMaterialDatePicker('setMinDate', new Date(current_date.getFullYear(), current_date.getMonth(), 1));
        //('#end_date').bootstrapMaterialDatePicker('setDate', new Date(current_date.getFullYear(), current_date.getMonth() + 1, 0));

        $("#delivery_day").select2({
            placeholder: " -- Select Day(s) --",
            templateSelection: function (a){return!!$(a.element).data("abbreviation")&&$(a.element).data("abbreviation")}
        });

        $("#campaign_status").select2({
            placeholder: " -- Select Status(s) --"
        });

        $("#country_id").select2({ placeholder: " -- Select Country(s) --"});

        $("#region_id").select2({
            placeholder: " -- Select Region(s) --",
            templateSelection: function (a){return!!$(a.element).data("abbreviation")&&$(a.element).data("abbreviation")}
        });

        // [ dark-toolbar ]
        $(function (){
            $("#filter-card-toggle").click(function (){
                if($(this).children('i').attr('class') == 'feather icon-minus-circle') {
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

            $('body').on('click', '#button-filter-apply, #reload-campaign-list', function(){
                CAMPAIGN_TABLE.ajax.reload();
            });

            $("#button-filter-reset").on('click', function(){
                $("#form-campaign-filters").find('input').val('');
                $("#form-campaign-filters").find('select').val('').trigger('change');
                CAMPAIGN_TABLE.ajax.reload();
            });

            $("#country_id").change(function () {
                if($(this).val() != '') {
                    $("#region_id").attr('disabled', 'disabled').val('').trigger('change');
                } else {
                    $("#region_id").removeAttr('disabled');
                }
            });

            $("#region_id").change(function () {
                if($(this).val() != '') {
                    $("#country_id").attr('disabled', 'disabled').val('').trigger('change');
                } else {
                    $("#country_id").removeAttr('disabled');
                }

            });

            //Set Filters
            var filters = localStorage.getItem('filters');

            if(filters != null) {
                var FILTERS = JSON.parse(filters);
                $.each(FILTERS, function (key, value){
                    $("#"+key).val(value);
                });
                $('.select2-multiple').trigger('change');
            }

            CAMPAIGN_TABLE = $('#table-campaigns').DataTable({
                "lengthMenu": [ [10, 25, 50, 100, 250], [10, 25, 50, 100, 250] ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('campaign.get_campaings')}}",
                    data: {
                        filters: function (){
                            var obj = {
                                start_date: $("#start_date").val(),
                                end_date: $("#end_date").val(),
                                campaign_status: $("#campaign_status").val(),
                                delivery_day: $("#delivery_day").val(),
                                due_in: $("#due_in").val(),
                                past_date: $("#past_date").val(),
                                country_id: $("#country_id").val(),
                                region_id: $("#region_id").val(),
                                campaign_type_id: $("#campaign_type_id").val(),
                                campaign_filter_id: $("#campaign_filter_id").val()
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
                    {
                        render: function (data, type, row) {
                            var percentage = (row.lead_detail.deliver_count/row.lead_detail.allocation)*100;
                            percentage = percentage.toFixed(2);
                            return '<div class="progress" style="height: 20px;width:100px;border:1px solid lightgrey;"><div class="progress-bar bg-warning text-dark" role="progressbar" aria-valuenow="'+percentage+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percentage+'%;font-weight:bold;">&nbsp;'+percentage+'%</div></div>';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var date = new Date(row.lead_detail.start_date);
                            return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+monthArray[date.getMonth()]+'/'+date.getFullYear();
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var date = new Date(row.lead_detail.end_date);
                            return (date.getDate() <= 9 ? '0'+date.getDate() : date.getDate())+'/'+monthArray[date.getMonth()]+'/'+date.getFullYear();
                        }
                    },
                    {
                        render: function (data, type, row) {
                            if(row.lead_detail.campaign_status == '{{\Modules\Campaign\enum\CampaignStatus::SHORTFALL}}') {
                                return row.lead_detail.deliver_count+' <span class="text-danger" title="Shortfall Count">('+row.lead_detail.shortfall_count+')</span>'+' / '+row.lead_detail.allocation;
                            } else {
                                return row.lead_detail.deliver_count+' / '+row.lead_detail.allocation;
                            }

                        }
                    },
                    {
                        render: function (data, type, row) {
                            switch (row.lead_detail.campaign_status) {
                                case '1': return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;">Live</span>';
                                case '2': return '<span class="badge badge-pill badge-warning" style="padding: 5px;min-width:50px;">Paused</span>';
                                case '3': return '<span class="badge badge-pill badge-danger" style="padding: 5px;min-width:50px;">Cancelled</span>';
                                case '4': return '<span class="badge badge-pill badge-primary" style="padding: 5px;min-width:50px;">Delivered</span>';
                                case '5': return '<span class="badge badge-pill badge-success" style="padding: 5px;min-width:50px;">Reactivated</span>';
                                case '6': return '<span class="badge badge-pill badge-secondary" style="padding: 5px;min-width:50px;">Shortfall</span>';
                            }
                            return ;
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

        });

        function getLeadDetails(id) {
            $.ajax({
                url: "{{route('campaign.get_lead_details')}}"+"/"+btoa(id),
                success: function (data){
                    $("#modal-lead-details").html(data);
                    $("#modal-lead-details").modal('show');
                }
            });
        }

        function editLeadDetail(id) {
            $.ajax({
                url: "{{route('campaign.get_lead_details')}}"+"/"+btoa(id),
                success: function (data){
                    $("#modal-lead-details2").html(data);
                    $("#modal-lead-details2").modal('show');
                }
            });
        }

        $(function (){
            $('body').on('submit', '#form-campaign-bulk-import', function(){
                var form_data = new FormData();
                var files = $('#campaign_file')[0].files;
                var url = $('#action-campaign-bulk-import').val();
                //console.log(url); console.log(files);
                if(files.length > 0 ) {
                    form_data.append('campaign_file',files[0]);
                    $.ajax({
                        url: url,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        xhr: function () {
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState == 2) {
                                    if (xhr.status == 201) {
                                        xhr.responseType = "text";
                                    } else {
                                        xhr.responseType = "blob";
                                    }
                                }
                            };
                            return xhr;
                        },
                        success: function(response, status, xhr) {
                            if(xhr.status == 200) {
                                var date = new Date();
                                var blob = new Blob([response], {type: '' + xhr.getResponseHeader("content-type")})
                                a = $('<a />'), url = URL.createObjectURL(blob);
                                a.attr({
                                    'href': url,
                                    'download': 'InvalidCampaigns_'+date.getTime()+'.xlsx',
                                    'text': "click"
                                }).hide().appendTo("body")[0].click();
                                trigger_pnofify('warning', 'Invalid Data', 'Campaigns imported with errors, please check excel file to invalid data.');
                            } else {
                                trigger_pnofify('success', 'Successful', response);
                            }
                            //console.log(response); console.log(xhr); console.log(xhr.responseType);
                            $('body').find("#get-campaign-import-modal").modal('hide');
                            CAMPAIGN_TABLE.ajax.reload();
                        }
                    });
                } else {
                    alert('Please select a file to upload');
                }
                return false;

            });
        });

    </script>
@append
