@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ url('/search_tweets') }}" enctype="application/x-www-form-urlencoded">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="sr-only" for="username"></label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="@twitter_handle" value={{$user}}>
                        <button type="submit" class="col-md-12 btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" align="center">
                {{$total_tweets}} total tweets searched
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class=" table table-striped ">
                    <tbody>
                    <tr>
                        <td > Username </td>
                        <td > Count </td>
                        <td > Recent Tweets</td>
                    </tr>

                    @if(isset($twitter_accounts))
                        @foreach($twitter_accounts as $account)
                            <tr>
                                <td class="align-middle">
                                    <p>{{$account['user']}}</p>

                                    <button class="btn-sm btn-secondary btn-analyze" >Analyze</button>
                                </td >
                                <td class="align-middle">{{$account['count']}}</td>
                                <td class="align-middle">
                                    @foreach($account['tweets'] as $tweet)
                                        <ol >
                                            {{$tweet['time']}} - {{$tweet['tweet']}}
                                        </ol>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @include('twitter.user_profile_modal')

    <script type="text/javascript">

        $('.btn-analyze').click(function(e) {

            e.preventDefault();

            var user = $(this).parent().find('p');
            $.ajax({
                type: "GET",
                url: '/search_user',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { username: user.text()},
                success: function( response ) {
                    var json = $.parseJSON(response);
                    console.log(json);

                    $("#twitter-screen-name").text(json.screen_name);
                    $("#twitter-name").text(json.name);
                    $("#twitter-location").text(json.location);
                    $("#twitter-statuses").text(json.statuses_count);
                    $("#twitter-followers").text(json.followers_count);
                    $("#twitter-friends").text(json.friends_count);


                    $("#myModal").modal("toggle");
                }
            });

        });

    </script>
@endsection
