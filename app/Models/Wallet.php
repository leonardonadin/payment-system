<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'balance',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the wallet as payer.
     */
    public function transactionsHasPayer()
    {
        return $this->hasMany(Transaction::class, 'payer_wallet_id');
    }

    /**
     * Get the transactions for the wallet as payee.
     */
    public function transactionsHasPayee()
    {
        return $this->hasMany(Transaction::class, 'payee_wallet_id');
    }
}
