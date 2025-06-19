<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:branches',
            'address' => 'required|string|max:255',
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Филиал создан успешно.');
    }

    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

     public function edit(Branch $branch)
    {
         if (!$branch) {
            abort(404); // Или перенаправьте с сообщением об ошибке
        }
         return view('branches.edit', compact('branch'));
     }


     public function update(Request $request, Branch $branch)
     {
        if (!$branch) {
            abort(404); // Или перенаправьте с сообщением об ошибке
        }
        $request->validate([
            'name' => ['required','string','max:255',
             \Illuminate\Validation\Rule::unique('branches')->ignore($branch->id)],
            'address' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Филиал обновлен успешно.');
    }


      public function destroy(Branch $branch)
    {
        if (!$branch) {
            abort(404); // Или перенаправьте с сообщением об ошибке
        }
         $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Филиал удален успешно.');
    }
}
