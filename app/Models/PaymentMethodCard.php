<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethodCard extends Model
{
    use HasFactory;

    protected $table = 'payment_methodcard'; 
    protected $primaryKey = 'payment_method_id'; 
    public $timestamps = false; 

    protected $fillable = [
        'payment_method_id',
        'card_number',       
        'card_holder_name',  
        'expiry_date',       
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
    ];


    /**
     * Define a relationship with the PaymentMethod model.
     * Each card payment method belongs to a single payment method.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }
}
