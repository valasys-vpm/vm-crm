@extends('layouts.master')

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
                                        <li class="breadcrumb-item"><a href="{{ route('campaign_type') }}">Campaign Type Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Edit Campaign Type</a></li>
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
                                            <h5>Edit Campaign Type</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-campaign-type-edit" method="post" action="{{ route('campaign_type.update', base64_encode($resultCampaignType->id)) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="full_name">Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="full_name" name="full_name" placeholder="Enter name" value="{{ $resultCampaignType->full_name }}">
                                                            </div>

                                                            <div class="col-md-6 form-group">
                                                                <label for="name">Abbreviation<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter abbreviation" value="{{ $resultCampaignType->name }}">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="status">Status</label>
                                                                <select class="form-control btn-square" id="status" name="status">
                                                                    @foreach(\Modules\Campaign\enum\CampaignTypeStatus::CAMPAIGN_TYPE_STATUS as $status => $value)
                                                                        <option value="{{ $status }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-square">Save</button>
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
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script>
        $("#status").val('{{ $resultCampaignType->status }}');
        $(function(){
            $("#form-campaign-type-edit").validate({
                focusInvalid: false,
                rules: {
                    'name' : {
                        required : true,
                        remote : {
                            url : '{{ route('campaign_type.validate_name') }}',
                            data : {
                                campaign_type_id : '{{ base64_encode($resultCampaignType->id) }}'
                            }
                        }
                    },
                    'full_name' : { required:true }
                },
                messages: {
                    'name' : {
                        required: "Please enter campaign type abbreviation.",
                        remote: "Campaign type already exists."
                    },
                    'full_name' : {
                        required: "Please enter campaign type name.",
                    },
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
    </script>
@append
