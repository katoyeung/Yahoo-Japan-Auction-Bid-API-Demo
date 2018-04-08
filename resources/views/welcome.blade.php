<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bid Master Demo</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 20vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }


            .stylish-input-group .input-group-addon{
                background: white !important;
            }
            .stylish-input-group .form-control{
                border-right:0;
                box-shadow:0 0 0;
                border-color:#ccc;
            }
            .stylish-input-group button{
                border:0;
                background:transparent;
            }
            .cliente {
                margin-top:10px;
                border: #cdcdcd medium solid;
                border-radius: 10px;
                -moz-border-radius: 10px;
                -webkit-border-radius: 10px;
                text-align:center;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-right links">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ Config::get('app.yahoo_api_url') }}" target="_blank">Admin</a>
            </div>
            <div class="row">
                <div class="col-lg-12">
                <div class="container">
                    <div class="row">
                        <form action="{{ route('bid.index') }}" method="GET">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div id="imaginary_container">
                                <div class="input-group stylish-input-group">

                                    <input type="text" class="form-control" name="keyword" placeholder="Search Auction Link / Auction ID / Category / Keyword" required>
                                    <span class="input-group-addon">
                                        <button type="submit">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>

                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="flex-center position-ref">
            <div class="container">
                <div class="row" style="margin: 0 auto; text-align:center">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ Session::pull('status') }} You can check the status in admin page.
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ Session::pull('error') }}
                        </div>
                    @endif

                    @if(isset($item))
                        <div class="col-md-6 col-md-offset-3" style="padding:10px">
                            <form class="form-inline" action="{{ route('bid.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="itemId" value="{{ $item->AuctionID }}" />
                                <input type="hidden" name="quantity" value="1" />

                                <div class="col-md-6">
                                    <label class="radio-inline"> <input type="radio" name="type" id="bidType" value="bid" checked> Bid </label>
                                    <label class="radio-inline"> <input type="radio" name="type" id="buyType" disabled="disabled"> Buy </label>
                                    <label class="radio-inline"> <input type="radio" name="type" id="scheduleType" disabled="disabled"> Schedule </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" id="bid" placeholder="Bid Price" name="bid" min="11" max="100" style="width:100px">
                                    <button type="submit" class="btn btn-primary">Bid</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-12">
                            <div style="text-align:left;">
                                @php
                                    dd($item);
                                @endphp
                            </div>
                        </div>
                    @elseif(isset($items))
                        @foreach($items as $key => $item)
                            @if($key % 4 === 0)
                                </div><div class="clearfix"></div><div class="row">
                            @endif
                            <div class="col-md-3">
                                <div class="cliente">
                                    <a href="{{ route('bid.show', $item->AuctionID) }}">
                                        <img src="{{ $item->Image }}" width="200px" /> <br />
                                        {{ str_limit($item->Title, 30, '...') }} <br />
                                        ¥{{ $item->CurrentPrice }} {{ $item->Bids ? '('.$item->Bids.')' : '' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>

