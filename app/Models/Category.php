<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\categoryPost;

class Category extends Model
{
    use HasFactory;

    # count() --> to display the number of post for each
    # category
    # Use this method to get the number of categories for each post
    public function categoryPost(){
        # Note: CategoryPost -- representing the PIVOT table (category_post) table
        return $this->hasMany(CategoryPost::class);
    }
}