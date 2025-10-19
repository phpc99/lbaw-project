<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_method'; 
    protected $primaryKey = 'payment_method_id'; 
    public $timestamps = false; 

    protected $fillable = [
        'choice', 
    ];

    /**
     * Define a one-to-one relationship with the PaymentMethodPayPal model.
     */
    public function paypal(): HasOne
    {
        return $this->hasOne(PaymentMethodPayPal::class, 'payment_method_id', 'payment_method_id');
    }

    /**
     * Define a one-to-one relationship with the PaymentMethodCard model.
     */
    public function card(): HasOne
    {
        return $this->hasOne(PaymentMethodCard::class, 'payment_method_id', 'payment_method_id');
    }

    /**
     * Define a one-to-one relationship with the PaymentMethodMBWay model.
     */
    public function mbway(): HasOne
    {
        return $this->hasOne(PaymentMethodMBWay::class, 'payment_method_id', 'payment_method_id');
    }
}
