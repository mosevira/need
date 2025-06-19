<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
