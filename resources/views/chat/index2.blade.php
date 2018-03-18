<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>聊天室</title>
</head>
<style>
    .chat-box{
        width: 100%;
        height: 300px;
        border: 1px solid #ccc;
    }
    p{
        margin: 0;
        padding: 0;
    }
</style>
<body>
<div class="chat-box">

</div>
<input type="text" id="content">
<button onclick="sendMsg()">发送</button>
</body>
</html>
<script src="{{URL::asset('js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('/js/websocket.js')}}" type="text/javascript"></script>
