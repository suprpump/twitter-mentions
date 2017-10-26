@include('layouts.master')
{{--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">--}}
    {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<h4 class="modal-title" align="center" id="exampleModalLabel">Register an Affiliate</h4>--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
            {{--</div>--}}
            {{--<form action="" method="POST">--}}
                {{--{{ csrf_field() }}--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="name" class="control-label">User Name:</label>--}}
                        {{--<input type="text" class="form-control" name="name" id="name" required>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="password" class="control-label">Password:</label>--}}
                        {{--<input type="text" class="form-control" name="password" id="password" required>--}}
                    {{--</div>--}}
                    {{--<button type="submit" name="submit_button" id="submit_button" class="btn btn-primary btn-lg btn-block" >Submit</button>--}}
                {{--</div>--}}
            {{--</form>--}}
            {{--<div class="modal-footer">--}}
            {{--</div>--}}
            {{--<p id="myModal" align="center">Privacy Policy: We hate SPAM </p>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title align-middle" id="twitter-user">User: <span id="twitter-user"></span></h1>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="name" class="control-label">User Name:</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{--@include('layouts.errors')--}}


