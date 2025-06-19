<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        $roles = ['admin', 'storekeeper', 'seller']; // Список ролей
        $branches = Branch::all(); // Получение всех филиалов
        return view('admin.users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'start_job_date' => 'nullable|date',
            'end_job_date' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,storekeeper,seller',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'start_job_date' => $request->start_job_date,
            'end_job_date' => $request->end_job_date,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => boolval($request->is_active ?? true), // Преобразуем в boolean
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно создан.');
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'start_job_date' => 'nullable|date',
            'end_job_date' => 'nullable|date',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,storekeeper,seller',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'nullable|boolean',
        ]);
        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'start_job_date' => $request->start_job_date,
            'end_job_date' => $request->end_job_date,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'is_active' => $request->has('is_active'), // Используем $request->has()
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно обновлен.');
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'storekeeper', 'seller'];
        $branches = Branch::all();
        return view('admin.users.edit', compact('user', 'roles', 'branches'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удален.');
    }

    public function toggleActive(User $user)
    {
        
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Статус пользователя успешно изменен.');
    }
}
