@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-4">
            <div class="user-wrapper">
                <ul class="users">
                    @foreach($users as $user)
                    <li class="user" id="{{$user->id}}">

                        @if($user->unread)
                        <span class="pending">{{$user->unread}}</span>
                        @endif

                        <div class="media">
                            <div class="media-left">
                                <img src="{{$user->avatar}}" alt="" class="media-object">
                            </div>
                            <div class="media-body">
                                <p class="name">{{$user->name}}</p>
                                <p class="email">{{$user->email}}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-8" id="messages">
            
        </div>

    </div>  
</div>
@endsection

@section('css')
    <style type="text/css">
        ul{
            padding: 0;
            margin: 0;
        }
        li{
            list-style: none;
        }
        .user-wrapper , .message-wrapper{
            border: 1px solid #dddddd;
            overflow-y: auto;
        }
        .user-wrapper{
            height: 500px;
        }
        .user{
            cursor: pointer;
            padding: 5px 0;
            position: relative;
        }
        .user:hover{
            background: #eeeeee;
        }
        .user:last-child{
            margin-bottom: 0;
        }
        .pending{
            position: absolute;
            left: 13px;
            top: 9px;
            background: #b600ff;
            margin : 0;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            padding-left: 5px;
            color: #ffffff;
            font-size: 12px;
        }
        .media-left{
            margin: 0 10px;
        }
        .media-left img{
            width: 64px;
            border-radius: 64px;
        }
        .media-body p{
            padding: 6px 0;
        }
        .message-wrapper{
            padding: 10px;
            height: 500px;
            background: #eeeeee;
        }
        .messages.message{
            margin-bottom: 15px;
        }
        .messages.message:last-child{
            margin-bottom: 0;
        }
        .receiver , .sent{
            width: 45%;
            padding: 3px 10px;
            border-radius: 10px;
            margin: 5px 0;
        }
        .receiver{
            background: #ffffff;
        }
        .sent{
            background: #3bebff;
            float: right;
            text-align: right;
        }
        .message p {
            margin: 5px 0;
        }
        .date{
            color: #777777;
            font-size: 12px;
        }
        .active{
            background: #eeeeee;
        }
        input[type=text]{
            width: 100%;
            padding: 12px 20px;
            margin : 15px 0 0 0;
            display: inline-block;
            border-radius: 4px;
            box-sizing: border-box;
            border : 1px solid #cccccc;
            outline: none;
        }
        input[type=text]:focus{
            border : 1px solid #aaaaaa;
        }
    </style>
@endsection

@section('js')
{{-- https://docs.google.com/document/d/1IBuxs6QShoiQ8azPh_6QQU_CoQpKaVly0-jWaLK9QMw/edit --}}
{{-- https://viblo.asia/p/su-dung-pusher-trong-laravel-tao-thong-bao-realtime-bJzKmX9B59N --}}
    <script type="text/javascript">
        $(document).ready(function(){
            // 419 (unknown status) , CSRF token mismatch
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });

            var id_logged = "{{Auth::id()}}";
            var receiver_id = '';

            // pusher
                // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('764631d04c12ca727b97', {
              cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
              // alert(JSON.stringify(data));
                if(id_logged == data.from){
                    $("#"+data.to).click();
                }else if(id_logged == data.to){
                    if(receiver_id == data.from){
                        // if receiver is selected , reload notification the selected user
                        $("#"+data.from).click();

                    }else{
                        // if receiver is not selected , add notification for that user
                        var pending = parseInt($("#" + data.from).find('.pending').html());
                        if(pending){
                            $("#"+data.from).find('.pending').html(pending + 1);
                        }else{
                            $("#"+data.from).append('<span class="pending">1</span>');
                        }
                    }
                    
                }
            });


            //


            
            $('.user').click(function(){
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.pending').remove();
                receiver_id = $(this).attr('id');

                $.ajax({
                    url : "message/"+receiver_id,
                    type : 'get',
                    data:"",
                    success: function(data){
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                });

            })

            $(document).on('keyup','.input-text input',function(e){
                var message = $(this).val();
                if(e.keyCode == 13 && message != '' && receiver_id != ''){  // 13 : enter
                    $(this).val('');

                    var data_str = "receiver_id=" + receiver_id + "&message=" + message;
                    $.ajax({
                        type : "post",
                        url : "/message",
                        data : data_str,
                        success : function(data){
                            scrollToBottomFunc();
                        },
                        complete : function(){

                        }
                    })
                }
                
            })
        });

        // make a function scroll down auto
        function scrollToBottomFunc(){
            $('.message-wrapper').animate({
                scrollTop: $('.message-wrapper').get(0).scrollHeight
            },50);
        }
    </script>

@endsection