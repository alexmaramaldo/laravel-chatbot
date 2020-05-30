<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="content" id="app">
        <div class="chat-container">
            <botman-tinker id="botmantag" user-id="" api-endpoint="/botman"></botman-tinker>
        </div>
    </div>
</div>

<script src="/js/app.js"></script>
<script>
    document.getElementById('attachment').style.display = 'none';
    document.getElementsByTagName('label')[0].style.display = 'none';
</script>
</body>
</html>
