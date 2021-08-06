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
                    <input type="file" id="campaign_file" name="campaign_file" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-campaign-bulk-import">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
