<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public static function accessibleBranches()
{
    return Auth::user()->isAdmin()
        ? self::all()
        : self::where('id', Auth::user()->branch_id)->get();
}
}
