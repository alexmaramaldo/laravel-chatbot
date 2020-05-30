<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <div class="content" id="app">
        <div class="chat_window"><div class="top_menu"><div class="buttons"><div class="button close"></div><div class="button minimize"></div><div class="button maximize"></div></div><div class="title">Chat</div></div><ul class="messages"></ul><div class="bottom_wrapper clearfix"><div class="message_input_wrapper"><input class="message_input" placeholder="Type your message here..." /></div><div class="send_message"><div class="icon"></div><div class="text">Send</div></div></div></div><div class="message_template"><li class="message"><div class="avatar"></div><div class="text_wrapper"><div class="text"></div></div></li></div>
    </div>
<script src="/js/app.js"></script>

</body>
</html>
