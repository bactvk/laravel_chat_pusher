<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use Auth;
use App\Message;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() // admin1234
    {
        //select all users except logged om user
        // $users = User::where('id','!=',Auth::id())->get();
        // count how many message are unread from the selected user
   
        $users = User::select("users.id" ,"users.avatar", "users.name" , "users.email" ,
                        \DB::raw("COUNT(messages.is_read) as unread") )
                        -> leftJoin('messages',function($join){
                            $join -> on('users.id','messages.from')
                                  -> where('messages.is_read',0)
                                  -> where('messages.to',Auth::id());
                        })
                        -> where('users.id','!=',Auth::id())
                        -> groupBy('users.id','users.name','users.email','users.avatar')
                        -> get();


        
        return view('home',['users' => $users]);
    }

    
}
