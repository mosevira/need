<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
       // Получаем статистику пользователей по ролям
       $roleCounts = $this->getUserRoleCounts();
       $salesData = $this->getUserRoleCounts();
        return view('admin.dashboard', compact('user','roleCounts', 'salesData'));
    }
    private function getUserRoleCounts()
    {
        return User::select('role', \DB::raw('count(*) as count'))
                    ->groupBy('role')
                    ->get()
                    ->map(function ($item) {
                        $item->role = $this->translateRole($item->role);
                        return $item;
                    });
    }

    private function translateRole($role)
    {
        $roles = [
            'admin' => 'Администратор',
            'seller' => 'Продавец',
            'storekeeper' => 'Кладовщик',
        ];

        return $roles[$role] ?? ucfirst($role); // Возвращаем перевод или исходную роль, если не нашли
    }
}
