<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payer_wallet_id',
        'payee_wallet_id',
        'amount',
        'status',
        'description',
    ];

    /**
     * Get the wallet that received the transaction.
     */
    public function payee()
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id');
    }

    /**
     * Get the wallet that paid for the transaction.
     */
    public function payer()
    {
        return $this->belongsTo(Wallet::class, 'payer_wallet_id');
    }

    /**
     * Get the transaction history for the transaction.
     */
    public function history()
    {
        return $this->hasOne(TransactionHistory::class);
    }
}
