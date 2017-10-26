@include('layouts.master')

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" align="center" id="exampleModalLabel">Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">Screen Name:</div>
                    <div align="left" class="col-sm-8" id="twitter-screen-name"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Name: </div>
                    <div align="left" class="col-sm-8" id="twitter-name"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Location: </div>
                    <div class="col-sm-8" id="twitter-location"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Statuses: </div>
                    <div class="col-sm-8" id="twitter-statuses"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Followers: </div>
                    <div class="col-sm-8" id="twitter-followers"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Friends: </div>
                    <div class="col-sm-8" id="twitter-friends"></div>
                </div>
                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


