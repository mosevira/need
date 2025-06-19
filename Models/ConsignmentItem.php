<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'consignment_id',
        'product_id',
        'quantity',
    ];

    public function consignment()
    {
        return $this->belongsTo(Consignment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
