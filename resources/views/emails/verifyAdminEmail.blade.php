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

        #ButtonHolder{
            width: auto;
            max-width: 100%;
            height: auto;
            position: relative;
            display: -webkit-flex; /* Safari */
            -webkit-justify-content: center; /* Safari 6.1+ */
            display: flex;
            justify-content: center;
        }
        .button{
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: none;
            font-size: 14px;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            transition: all ease-in-out 200ms;
            color: white;
            border: none;
            line-height: inherit;
            font-weight: bold;
        }
        .button:hover{
            transform: scale(1.00);
            color: #ffffff;
            text-shadow:  -1px 4px 10px rgba(20,20,20,.8);
        }
        .button:active{
            transform: translateY(3px) scale(.97);
            outline: none;
        }
        .button-sm{
            padding: 0.3rem 0.8rem!important;
        }

        .Blue_button {
            box-shadow: -1px 10px 20px #9BC6FD;
            background: #52A0FD;
            background: -moz-linear-gradient(left,  #52A0FD 0%, #00C9FB 70%, #00C9FB 100%);
            background: -webkit-linear-gradient(left,  #52A0FD 0%, #00C9FB 70%, #00C9FB 100%);
            background: linear-gradient(to right,  #52A0FD 0%, #00C9FB 70%, #00C9FB 100%);
        }.Blue_button:hover{
             box-shadow: -1px 4px 10px #9BC6FD;
         }

        .Red_button {
            box-shadow: -1px 10px 20px #FF7DAE;
            background: #FF1744;
            background: -moz-linear-gradient(left,  #FF1744 0%, #FF5D95 70%, #FF5D95 100%);
            background: -webkit-linear-gradient(left,  #FF1744 0%, #FF5D95 70%, #FF5D95 100%);
            background: linear-gradient(to right,  #FF1744 0%, #FF5D95 70%, #FF5D95 100%);
        }.Red_button:hover{
             box-shadow: -1px 4px 10px #FF7DAE;
         }

        .Green_button {
            box-shadow: -1px 10px 20px #84e184;
            background: #84e184;
            background: -moz-linear-gradient(left,  #248f24 0%, #33cc33 70%, #84e184 100%);
            background: -webkit-linear-gradient(left,  #248f24 0%, #33cc33 70%, #84e184 100%);
            background: linear-gradient(to right,  #248f24 0%, #33cc33 70%, #84e184 100%);
        }.Green_button:hover{
             box-shadow: -1px 4px 10px #84e184;
         }

        .CyanGreen_button{
            box-shadow: -1px 10px 20px #84e184;
            background: #84e184;
            background: -moz-linear-gradient(left,  #009999 0%, #00E466 70%, #66ff99 100%);
            background: -webkit-linear-gradient(left,  #009999 0%, #00E466 70%, #66ff99 100%);
            background: linear-gradient(to right,  #009999 0%, #00E466 70%, #66ff99 100%);
        }.CyanGreen_button:hover{
             box-shadow: -1px 4px 10px #84e184;
         }

    </style>
</head>
<body>

@php
    $url = url('admin/verification').'/'.$data['id'].'/'.$data['token'];
@endphp

<div class="container">
    <div class="header">
        <img src="{{ $data['logo'] }}" alt="" height="200" width="200">
        <h1>Hello, {{ $data['name'] }}!</h1>
    </div>

    <div class="body">
        {!! $data['message'] !!}

        <div id="ButtonHolder">
            <a href="{{ $url }}" class="button Blue_button">Verify Email</a>
        </div>
        <br>
        <p>If you are watching this by mistake please ignore this email.</p>
        <br>
        if the above button doesnot work please use this link: <a href="{{ $url }}">{{ $url }}</a>
        <br>
        Thanks,<br>
        {{ config('app.name') }}
    </div>
</div>

</body>
</html>



