<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warning;
use Auth;

use Pusher\Pusher;
use App\Events\PushWarningEvent;

class WarningAllController extends Controller
{
    public function add_warning(Request $request)
    {
    	if($request->isMethod('post')){
    		$data = [
    			'content' => $request->content,
    			'user_id' => Auth::user()->id
    		];
    		Warning::add($data);

    		event(new PushWarningEvent($data));

    		return redirect()->route('add_warning')->with('msg','Thêm thành công');
    	}
    	return view('warning.add');
    }
}
