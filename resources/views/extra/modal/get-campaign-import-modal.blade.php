<div id="get-campaign-import-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-campaign-bulk-import" method="post" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="action-campaign-bulk-import" name="action" value="{{ route('campaign.bulk.import') }}">

                <div class="modal-header">
                    <h5 class="modal-title">Import Campaign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Select excel file <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="campaign_file" name="campaign_file" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Select zip file (specification)</label>
                            <input type="file" class="form-control" id="specification_file" name="specification_file">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-campaign-bulk-import">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
