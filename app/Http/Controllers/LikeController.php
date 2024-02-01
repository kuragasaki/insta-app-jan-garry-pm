<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //this handles authenication
use Illuminate\Http\Request;
use App\Models\Like; //Note that the Like.php represents our likes table

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like){
        //Initializing the object
        $this->like = $like;
    }

    # This method is going to store the like into the likes table
    public function store($post_id){
        $this->like->user_id = Auth::user()->id; //owner of the liked
        $this->like->post_id = $post_id;         //the post being liked
        $this->like->save();                     //store to the likes table

        //redirect the user to the same page
        return redirect()->back();
    }

    # This method is going to delete/destroy the liked
    public function destroy($post_id){
        $this->like
            ->where('user_id', Auth::user()->id)
            ->where('post_id', $post_id)
            ->delete();

            return redirect()->back();
    }
}
