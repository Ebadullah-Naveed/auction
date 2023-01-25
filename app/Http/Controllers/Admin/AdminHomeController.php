<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper;
use DB;
use App\Models\Role;
use App\Models\User;

class AdminHomeController extends Controller
{
    public function index() {

        $data['total_users'] = User::where('role_id',null)->count('id');

        return view('admin.home',$data);
    }

}
