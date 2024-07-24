<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Siz admin emassiz']);
        }
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return auth()->user();
    }
    public function UserId($id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(["message" => "Afsuski siz admin emas ekansiz"]);
        }
        try {
            $user = User::findOrFail($id);
            return    $user = UserResource::make($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Bunday id egasi hali mavjud emas"]);
        }
    }

    public function UserUpdate(UpdateUserRequest $request, $id)
    {
        if (!auth()->user()) {
            return response()->json(['message' => "Siz hali ro'yxatdan o'tmagansiz"]);
        }
        try {
            $existingUser = User::where('username', $request->username)->first();
            if ($existingUser) {
                return response()->json(['message' => 'Bunday User Bor'], 400);
            } else {
                $user = User::findOrFail($id);
                $user->update($request->validated());
                return response()->json(["message" => "Muvaffaqiyatli yangilandi"]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Bunday id egasi topilmadi']);
        }
    }
    public function UserDelete(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user && $user->id == $id) {
                $user  = User::findOrFail($id);
                $user->delete();
                return response()->json(['message' => 'Muvaffaqiyatli o\'chirildi.'], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Bunday id topilmadi']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(["message" => "Afsuski siz admin emas ekansiz"]);
        }
        try {
            $existingUser = User::where('username', $request->username)->first();
            if ($existingUser) {
                return response()->json(['message' => 'Bunday User Bor'], 400);
            } else {
                $user = User::findOrFail($id);
                $user->update($request->validated());
                return response()->json([
                    "message" => "$user->id-id egasi Muvaffaqiyatli yangilandi"
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => "Bunday id egasi mavjud emas"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public  function deleteUser($id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Siz Admin emassiz']);
        }
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => "User muvaffaqiyatli O'chirildi"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Bu id egasi topilmadi"], 404);
        }
    }
    public function AdminUser(Request $request, $id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(["message" => "Afsuski siz admin emas ekansiz"]);
        }
        try {
            $user = User::findOrFail($id);
            $user->is_admin = $request->is_admin;
            $user->save();
            return response()->json(["message" => "Siz $user->username ni Admin qildingiz"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Bunday Id dagi user topilmadi"], 404);
        }
    }


    public function destroy($id)
    {
        $user = auth()->user();
        if (!auth()->user()) {
            return response()->json(['error' => "Siz hali ro'yxatdan o'tmagansiz"], 403);
        }
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => "User Muvaffaqiyatli o'chirildi"], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["message" => "Bunday id egasi topilmadi"]);
        }
    }
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(['message' => "Muvaffaqiyatli qo'shildi"]);
    }
}
