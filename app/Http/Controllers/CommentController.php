<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id){
        #1. Validate the data first
        $request->validate(
            [
                'comment_body' . $post_id => 'required|max:150'
            ],
            [
                'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
                'comment_body' . $post_id . '.max' => 'The comment must not be greater than 150 characters.'
            ]
        );

        #2. Get the data and save it to the Db
        $this->comment->body = $request->input('comment_body' . $post_id); //the actual comment
        $this->comment->user_id = Auth::user()->id;                         //owner of the comment
        $this->comment->post_id = $post_id;                                 //id of the post being commented
        $this->comment->save();//insert or save into the comments table

        # redirect the user to the same page
        return redirect()->back();
    }

    # This method is going to delete the post comment
    public function destroy($id){
        $this->comment->destroy($id);
        //Same as: "DELETE * FROM comments WHERE id = $id";

        return redirect()->back();//go back to the same page
    }
}
