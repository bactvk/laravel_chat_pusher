<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Auth;
use Pusher\Pusher;
use App\Events\PusherEvent;

class MessageController extends Controller
{
   public function getMessage($receiver_id)
    {   
        $id_login = Auth::id();
        // getting all message for selected user
        //getting those message which is from = Auth::id() and to = user_id OR from = user_id and to = Auth::id()

        $messages = Message::where(function($query) use ($receiver_id ,$id_login) {
            $query  ->  where('from',$id_login)
                    ->  where('to',$receiver_id);
        })->orWhere(function($query) use($receiver_id ,$id_login){
            $query  ->  where('from',$receiver_id)
                    ->  where('to',$id_login);
        })
        ->get();

        //update read when user click to see message 
        Message::where(['from' => $id_login , 'to' => $receiver_id ])->update(['is_read' => 1 ]);

        return view('messages.index',['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
    	$from = Auth::id();
    	$to = $request->receiver_id;
    	$message = $request->message;

    	$data = new Message;
    	$data->from = $from;
    	$data->to = $to;
    	$data->message = $message;
    	$data->is_read = 0;  // message will be unread when sending message

    	$data->save();

    	//pusher
  
		$data = [ 'message' => $message,'from' => $from , 'to' => $to];
	
		event(new PusherEvent($data));
    }
}
