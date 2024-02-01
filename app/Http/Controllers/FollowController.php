<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // the class that handles the authentication
use App\Models\Follow; // Note that the Follow.php represents our follows table

class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow){
        $this->follow = $follow;
    }

    public function store($user_id){
        $this->follow->follower_id = Auth::user()->id; //id of the user (follower)
        $this->follow->following_id = $user_id;        //id of user being followed
        $this->follow->save();                         //insert into follows table

        return redirect()->back();                     //return to the same page
    }

    public function destroy($user_id){
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

            return redirect()->back();
    }
}
