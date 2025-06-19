<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_branch_id', // С какого склада отправляется
        'to_branch_id', // В какой магазин отправляется
        'created_by', // Кто создал накладную
        'status', // Статус накладной (pending, sent, received, rejected)
        'is_incoming', // Приемка товара
    ];
    
    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}
