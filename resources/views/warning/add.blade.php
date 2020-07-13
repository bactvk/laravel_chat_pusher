<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

<style>
* {
  box-sizing: border-box;
}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}
</style>
</head>
<body>

<h2>Tạo mới cảnh báo</h2>
<p>Thông báo đến tất cả các user.</p>

<div class="container">
  @if(session('msg'))
    <div class="alert alert-success">
        <li style="color: green; font-weight: bold">{{session('msg')}}</li>
    </div>
  @endif
  <form action="" method="post">
    @csrf
    <div class="row">
      <div class="col-25">
        <label for="fname">Nội dung</label>
      </div>
      <div class="col-75">
        <input required="" type="text" id="content" name="content" placeholder="Nhập nội dung ....">
      </div>
    </div>
    
    <div class="row">
      <button type="button" value="Submit" id="save_warning">Submit</button>
    </div>
  </form>
</div>
      
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
      <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });

            Pusher.logToConsole = true;

            var pusher = new Pusher('764631d04c12ca727b97', {
              cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');

            channel.bind('my-event', function(data) {
             alert(JSON.stringify(data));
                

            });


            $('#save_warning').click(function(e){
                var message = $("#content").val();
                
                $(this).val('');

                var data_str = "content=" + message;
                $.ajax({
                    type : "post",
                    url : "/create_warning",
                    data : data_str,
                    success : function(data){
                        console.log('ok');
                    },
                    complete : function(){

                    }
                })   
            })

        })
    </script>
</body>
</html>