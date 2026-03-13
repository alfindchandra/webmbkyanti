<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debt extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class)->nullable();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'amount' => 'decimal:2',
        'debt_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
