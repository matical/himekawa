<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ff9800">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,400italic">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #212121;
            color: #eb6c24;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 64px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title">
            @yield('message')
        </div>
        <div>
            <a href="/"><img src="/chiruchiru.png" title="Return"></a>
        </div>
    </div>
</div>
</body>
</html>
