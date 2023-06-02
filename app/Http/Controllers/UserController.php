<?php

namespace App\Http\Controllers;

use App\Logic\FileLogic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(protected FileLogic $fileLogic)
    {
    }

    public function showSetting()
    {
        return view("setting");
    }

    public function updateImage(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), ['image' => "required|mimes:jpg,jpeg,png|max:10240"]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), HttpResponse::HTTP_BAD_REQUEST);
        }

        $this->fileLogic->storeUserImage($userId, $request, "image");
        return response()->json(["message" => "success"]);
    }

    public function updateData(Request $request)
    {
        $userId = Auth::user()->id;
        $request->validate([
            "name" => "required|min:1|max:35",
            "email" => 'unique:users,email,' . $userId . '|required|email',
        ]);

        $user = User::find($userId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with("notif", ["Success\nProfile updated successfully"]);
    }

    public function updatePassword(Request $request)
    {
        $userId = Auth::user()->id;
        $request->validate([
            "current_password" => "required",
            "new_password" => "required|confirmed|min:8|max:30",
            "new_password_confirmation" => "required"
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors("Wrong password please try again");
        }

        $user = User::find($userId);
        $user->password = bcrypt($request->new_password);
        $user->save();

        Auth::attempt(
            [
                "email" => $user->email,
                "password" => $request->new_password
            ],
            Auth::viaRemember()
        );

        return redirect()->back()->with("notif", ["Success\nPassword changed successfully"]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("login");
    }

    public function deactivate(Request $request)
    {
        $user = User::find($request->id);
        $user->is_active = false;
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("login")->with("notif", ["Success\nAccount Successfully Deleted!"]);
    }
}
