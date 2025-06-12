<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\DataTables\UsersDataTableEditor;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function import(Request $request) {
        $request->validate([
            'excel' => 'required|mimes:csv,xlsx,xls'
        ]);
        Excel::import(new UsersImport, request()->file("excel"));
        return response()->json("Excel is imported");
    }
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
    public function store() {
        $validator = Validator::make(request()->all(), [
            "name" => [ "required" ],
            "email" => [ "required", "email" ],
            "password" => [ "required" ]
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            ...$validator->validated(),
            "password" => Hash::make("password")
        ]);

        return response()->json($user);
    }
    public function update() {
        $user_id = request()->user_id;
        $user = User::find($user_id);

        if(!$user) {
            throw new ModelNotFoundException("User not found!");
        }

        $validator = Validator::make(request()->all(), [
            "name" => [ "required" ],
            "email" => [ "required", "email" ],
            "password" => [ "required" ]
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            ...$validator->validated(),
            "password" => Hash::make(request()->password)
        ]);

        return response()->json(["Data has been updated!"]);

    }
}
