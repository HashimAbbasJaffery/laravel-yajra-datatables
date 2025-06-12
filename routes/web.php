<?php

use App\DataTables\UsersDataTable;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::delete("user/delete", [UserController::class, "delete"])->name("user.delete");
Route::get("user/create", [UserController::class, "create"])->name("user.create");
Route::post("user/create", [UserController::class, "store"])->name("user.store");
Route::post("import", [UserController::class, "import"])->name("users.import");

Route::get("export-users", function(UsersDataTable $dataTable) {
    if(!auth()->user()) {
        return redirect(route("login.front"));
    }
    return $dataTable->render("users");
})->name("users.list");


Route::put("user/update", [UserController::class, "update"])->name("user.update");

Route::get("logout", function() {
    Auth::logout();
    return redirect(route("login.front"));
})->name("logout");

Route::get("login", function() {
    return view("login");
})->name("login.front");

Route::get("/auth/{provider}/redirect", [OAuthController::class, "redirect"])->name("login");
Route::get("/auth/{provider}/callback", [OAuthController::class, "callback"]);


// Route::get("users", function(DataTables $datatable) {

//     if(request()->ajax()) {
//         $model = User::all();
    
//         $dataTable = $datatable::of($model)
//                         ->addIndexColumn()
//                         ->addColumn("total_posts", function($row) {
//                             $total_posts = $row->posts()->count();
//                             if(!$total_posts) {
//                                 return "No Posts";
//                             }
//                             return $total_posts . " Posts";
//                         })
//                         ->addColumn("action", function($row) {
//                             $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>
//                                     <a style="color: white;" onclick="deleteRecord(' . $row->id . ', ' . $row->id . ')" class="delete btn btn-danger btn-sm">Delete</a>';
//                             return $btn;
//                         })
//                         ->setRowId('user-{{$id}}')

//                         ->toJson();
    
//         return $dataTable;
//     }

//     return view("datatable");
// })->name("users");

