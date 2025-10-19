<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'purchase';
    protected $primaryKey = 'purchase_id';
    public $timestamps = false;

    protected $fillable = [
        'purchase_date',
        'total',
        'delivery_progress',
        'user_id',
        'address_id',
        'payment_method_id',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    // Relationship: Belongs to a User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship: Belongs to an Address
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    // Relationship: Belongs to a Payment Method
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    // Relationship: Belongs to many Products (via pivot table 'purchase_product')
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'purchase_product',
            'purchase_id',
            'product_id'
        )->withPivot('quantity');
    }
}
