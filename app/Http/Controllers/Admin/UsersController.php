<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        // we will use the withTrashed() method to display the deactivate users
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        return view('admin.users.index')->with('all_users', $all_users);
    }

    public function deactivate($id)
    {
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id)
    {   
        //the onlyTrashed() retrieved the soft deleted records only
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
