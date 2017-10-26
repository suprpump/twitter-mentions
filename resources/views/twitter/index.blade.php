@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ url('/search_tweets') }}" enctype="application/x-www-form-urlencoded">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="sr-only" for="username"></label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="@twitter_handle">
                        <button type="submit" class="col-md-12 btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
