<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification Mail</title>

    <style>
        *{
            text-align: left;
        }
        .container{
            max-width: 80%;
            width: 500px;
            min-width: 500px;
            padding: 40px 20px;
            background-color: white;
            margin: 20px auto;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 0, 0, .6);
            border: 1px solid #666;
            border-radius: 6px;

            align-content: center;
            align-items: center;
            vertical-align: center;
            vert-align: middle;
        }
        .container .header h1{
            text-align: left;
            color: #666;
            font-size: 24px;
        }
        .header{
            padding: 10px 20px 0 20px;
        }
        .body{
            padding: 0 20px 10px 20px;
        }
        p{
            text-align: center;
            font-size: 14px;
        }
        .footer p{
            font-size: 12px!important;
        }
    </style>
</head>
<body>

@php
    $url= url('admin/');
@endphp

<div class="container">
    <div class="header">
        <img src="{{ $data['logo'] }}" alt="" height="200" width="200">
        <h1>Hello, {{ $data['name'] }}!</h1>
    </div>

    <div class="body">
        {!! $data['message'] !!}

        <p>If you are watching this by mistake please ignore this email.</p>
        <br>
        Link to website: <a href="{{ $url }}">{{ $url }}</a>
        <br>
        Thanks,<br>
        {{ config('app.name') }}
    </div>
</div>

</body>
</html>



