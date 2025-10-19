<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model {
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
        'payment_method_id'
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    // Section: has one (Payment Method)
    public function paymentMethod(): HasOne {
        return $this->hasOne(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }
    // Section: has many (Products)
    public function products(): BelongsToMany 
    {
            return $this->belongsToMany(
                Product::class,           // Related model
                'purchase_product',       // Pivot table
                'purchase_id',            // Foreign key on the pivot table for this model
                'product_id'              // Foreign key on the pivot table for the related model
            )->withPivot('quantity');    // Include the 'quantity' pivot data
    }
    // Section: belongs to one (User)
    public function purchaseUser(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    // Section: belongs to one (Address)
    public function purchaseAddress(): BelongsTo {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }
    // Section: belongs to one (Payment Method)
    public function purchasePaymentMethod(): BelongsTo {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

}
