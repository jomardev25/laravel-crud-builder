<?php

namespace App\Http\Controllers\Modules\Maintenance;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('modules.maintenance.users.index');
    }
}
