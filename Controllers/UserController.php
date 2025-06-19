<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
         $users = User::with('branch')->get();
         return view('users.index', compact('users'));

    }

    // public function create()
    // {
    //      return view('users.create');
    // }

     public function store(Request $request)
    {
         $request->validate([
             'name' => 'required|string|max:255',
             'surname' => 'required|string|max:255',
              'patronymic' => 'nullable|string|max:255',
             'birth_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'start_job_date' => 'nullable|date',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,storekeeper,seller',
              'branch_id' => ['required',  'exists:branches,id']
         ]);
         User::create([
             'name' => $request->name,
             'surname' => $request->surname,
            'patronymic' => $request->patronymic,
             'birth_date' => $request->birth_date,
            'phone' => $request->phone,
             'start_job_date' => $request->start_job_date,
            'email' => $request->email,
             'password' => Hash::make($request->password),
             'role' => $request->role,
              'branch_id' => $request->branch_id
         ]);
        return redirect()->route('users.index')->with('success', 'Пользователь создан успешно.');
    }


     public function edit(User $user)
    {
         return view('users.edit', compact('user'));
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
             'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($user->id)],
              'role' => 'required|in:admin,storekeeper,seller',
             'branch_id' => ['required',  'exists:branches,id']

        ]);
          $user->update([
               'name' => $request->name,
              'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
             'start_job_date' => $request->start_job_date,
            'email' => $request->email,
             'role' => $request->role,
             'branch_id' => $request->branch_id,
         ]);
        if($request->password){
              $request->validate([
                   'password' => 'required|string|min:8|confirmed',
              ]);
              $user->password = Hash::make($request->password);
            $user->save();
        }
            return redirect()->route('users.index')->with('success', 'Пользователь обновлен успешно.');
    }

     public function destroy(User $user)
    {
           $user->delete();
        return redirect()->route('users.index')->with('success', 'Пользователь удален успешно.');
    }

    public function deactivate(User $user)
    {
          $user->is_active = false;
        $user->save();
        return redirect()->route('users.index')->with('success', 'Пользователь деактивирован успешно.');
    }

    public function activate(User $user)
     {
         $user->is_active = true;
         $user->save();
        return redirect()->route('users.index')->with('success', 'Пользователь активирован успешно.');
    }

    public function create()
    {
         $branches = Branch::all();
         return view('users.create', compact('branches'));
    }

}
