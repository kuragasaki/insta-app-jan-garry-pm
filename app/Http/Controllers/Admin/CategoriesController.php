<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; //represents the categories table
use App\Models\Post; //represents the posts table

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post)
    {
        $this->category = $category;
        $this->post = $post;
    }

    public function index()
    {
      $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(5);
        
      //initialized empty property
      $uncategorized_count = 0;
      $all_posts = $this->post->all();
    
      foreach ($all_posts as $post) {
        if ($post->categoryPost->count() == 0) { //if the count() is equal to zero
            $uncategorized_count++; //1
        }
      }

      return view('admin.categories.index')
        ->with('all_categories', $all_categories)
        ->with('uncategorized_count', $uncategorized_count);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);

        # strtolower - to convert all character to lowercase
        # ucwords - use to convert the first character to uppercase
        $this->category->name = ucwords(strtolower($request->name));
        $this->category->save(); //insert into categories table

        return redirect()->back();   
    }

    public function update(Request $request, $id){
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name'
        ]);
        $category = $this->category->findOrFail($id);
        $category->name = ucwords(strtolower($request->new_name));
        $category->save();

        return redirect()->back();
    }

    public function destroy($id){
        $this->category->destroy($id);
        return redirect()->back();
    }
}
