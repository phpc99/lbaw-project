<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'user_id'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
