var web_server = {
    config:{
        url:'ws://127.0.0.1:9501'
    },
    init:function () {
        this.open();
        this.message();
        this.close();
        this.error();
    },
    server:new WebSocket('ws://127.0.0.1:9501'),
    open:function () {
        this.server.onopen = function () {
            console.log("open")
        }
    },
    message:function () {
        this.server.onmessage= function(e){
            var data = $.parseJSON(e.data);
            var html = '';
            if(data.msg_type == 3 || data.msg_type == 4){
                html = '<p style="text-align: center;font-size: 12px;color: #ccc;">'+data.msg+'</p>'
            }else if(data.msg_type == 1 || data.msg_type == 2){
                html = '<p>'+data.user_info.name+':'+data.msg+'</p>';
            }else if(data.msg_type == 5){
                html = '<p style="text-align: center;font-size: 12px;color: #ccc;">公告：'+data.msg+'</p>'
            }

            $('.chat-box').append(html);
            console.log(data)
        }
    },
    close:function () {
        this.server.onclose = function () {
            console.log('close')
        }
    },
    error:function () {
        this.server.onerror = function () {
            console.log('erroe');
        }
    },
    clearInput:function () {
        $('#content').val('');
    },
    appendSelfHtml:function (content) {
        var html = '<p style="text-align: right">'+content+'：我</p>';
        $('.chat-box').append(html);
    },
    sendMsg:function () {
        var msg = $('#content').val();
        var data = {
            msg:msg,
            msg_type:2,
            toFd:[]
        };
        data = JSON.stringify(data);
        this.server.send(data);
        this.clearInput();
        this.appendSelfHtml(msg);
    }
};

web_server.init();
var sendMsg = function () {
    console.log('发送');
    web_server.sendMsg();
    web_server.close();
};
