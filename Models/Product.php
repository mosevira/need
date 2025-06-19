<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

  protected $fillable = [
    'name',
    'article',
    'barcode', // Добавляем поле для штрих-кода
    'price',
    'quantity',
    'branch_id'
];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function saleitem()
    {
        return $this->hasMany(Branch::class);
    }
    public static function findByBarcode($barcode)
{
    return self::where('barcode', $barcode)->first();
}
}
