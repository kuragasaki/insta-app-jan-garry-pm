<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;      //this model represents our posts table
use App\Models\Category; //this model represents our categories table
use Illuminate\Support\Facades\Auth; //for authentication

class PostController extends Controller
{
    # Define properties
    private $post;
    private $category;

    # Define the constructor
    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
    }

    # This method is going to retrieved all the categories
    # from the categories table
    public function create(){
        $all_categories = $this->category->all(); //retrieves all the categories

        # Go to create.blade.php page and show/display the categories
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    # Received the data coming from the form (create.blade.php)
    # We need to request for that data
    public function store(Request $request){
        #1. Validate the data first
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        #2. Save the data into the Database
        $this->post->user_id = Auth::user()->id; //the id of the user (owner of the post)
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save(); //insert the data into the database table

        #3. Save the categories id
        # Example: $request->category contains 3 categories --- Travel (1), Lifestyle(3), Movie(6)
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);


        #4. Return to homepage
        return redirect()->route('index');
    }

    public function show($id){
        # search for the post
        $post = $this->post->findOrFail($id);

        # Go to the show.blade.php with the data from $post
        return view('users.posts.show')
            ->with('post', $post);
    }

    public function edit($id){ //$id is the id of the post
        $post = $this->post->findOrFail($id); //search for the post; id = 4
        #$post['id' => 4, 'description => 'I want to travel', 'image' => data:image/png;base64,iVBORw0, 'created_at' => 2024-01-16 09:10:49 ];

        # Check if the AUTH USER is not the owner of the post
        # If, it's not the owner of the post, then redirect to the homepage
        if (Auth::user()->id != $post->user->id) {
            return redirect()->route('index'); //homepage
        }

        # retrieved all the lists of categories in the categories table
        $all_categories = $this->category->all();
        #$all_categories[Travel, Food, Lifestyle, Technology, Movie, Career]


        # Get all the categories of this post, and save it in the array
        $selected_categories = []; //initialized an empty array
        foreach ($post->categoryPost as $category_post) { //1,3,5
            $selected_categories[] = $category_post->category_id;
        }

        # render the view with the data into edit.blade.php
        return view('users.posts.edit')
            ->with('post', $post)                                //posts details
            ->with('all_categories', $all_categories)             //categories in categories table
            ->with('selected_categories', $selected_categories);  //categories under the post
    }

    public function update(Request $request, $id){
        #1. Validate the data coming from the form first
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'mimes:jpeg,jpg,png,gif|max:1048' 
        ]);

        #2. Update the post
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;


        #3. Check if there is a new image uploaded
        if ($request->image) {
            $post->image = 'data:image/'. $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        #4. Save datails to the post table
        $post->save(); //insert the data above to the posts table


        #5. Delete all the records from the category_post related post
        $post->categoryPost()->delete(); //deleting all the previous selected categories
        # Note: Use the relationship  Post::categoryPost() to select the records related post
        # Equivalent to: DELETE FROM category_post WHERE post_id = $id

        #6. Save the new category list selected by the user to the category_post table
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        #7. Return to the Show Post Page (to confirm the update is successful)
        return redirect()->route('index'); //homepage ---temporary --- we will change it to post page
    }

    public function destroy($id){

        $post = $this->post->findOrFail($id);
        $post->forceDelete();
        return redirect()->route('index');


        # Same as: "DELETE * FROM posts WHERE id = $id";
        #$this->post->destroy($id);

        # After deleting the post, redirect the user to the homepage
        #return redirect()->route('index'); //homepage
    }
}
