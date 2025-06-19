<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

 protected $fillable = [
        'branch_id',
        'number',
        'date',
        'status',
        'created_by',
        'accepted_by',
        'closed_at'
    ];
protected $casts = [
    'status' => InvoiceStatus::class,
];
    // Статусы из ТЗ
    const STATUS_PROCESSING = 'processing';
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_IN_STORE = 'in_store';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DISCREPANCY = 'discrepancy';
    const STATUS_CLOSED = 'closed';

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acceptor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function getStatusTextAttribute(): string
    {
        return [
            self::STATUS_PROCESSING => 'В обработке',
            self::STATUS_OPEN => 'Открыта',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_IN_STORE => 'В магазине',
            self::STATUS_ACCEPTED => 'Принята',
            self::STATUS_DISCREPANCY => 'Принята с неточностью',
            self::STATUS_CLOSED => 'Закрыта'
        ][$this->status];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_DISCREPANCY => 'warning',
            self::STATUS_CLOSED => 'secondary',
            default => 'primary'
        };
    }

    public function updateStatus(): void
    {
        if ($this->items()->whereNull('accepted_quantity')->exists()) {
            $this->status = self::STATUS_IN_STORE;
        } elseif ($this->items()->whereColumn('accepted_quantity', '!=', 'quantity')->exists()) {
            $this->status = self::STATUS_DISCREPANCY;
        } else {
            $this->status = self::STATUS_ACCEPTED;
        }
        $this->save();
    }

    public function close(): void
    {
        if (!in_array($this->status, [self::STATUS_ACCEPTED, self::STATUS_DISCREPANCY])) {
            throw new \LogicException('Невозможно закрыть накладную с текущим статусом');
        }

        $this->status = self::STATUS_CLOSED;
        $this->closed_at = now();
        $this->save();
    }
}
