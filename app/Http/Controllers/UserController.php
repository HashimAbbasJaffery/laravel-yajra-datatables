<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function index(Request $request) {
        if($request->ajax()) {
            $user = User::all();

            $datatable = DataTables::of($user)
                            ->addIndexColumn()
                            ->addColumn("action", function($row) {
                                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                                return $btn;
                            })
                            ->make(true);

            return $datatable;
        }

        return view("datatable");
    }

    public function delete() {
        $user_id = request()->user_id;
        $user = User::find($user_id);
        if(!$user) {
            throw new ModelNotFoundException("User not found!");
        }

        $user->delete();

    }

    public function create() {
        return view("create");
    }
}
