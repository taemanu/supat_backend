<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index() {
        $data = User::all();
        return $this->ok($data);
    }
}
