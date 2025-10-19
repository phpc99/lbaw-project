<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review'; 
    protected $primaryKey = 'review_id'; 
    public $incrementing = true; // Se for auto-incrementável
    protected $keyType = 'int';  // Tipo da chave primária
    public $timestamps = false; 

    protected $fillable = [
        'score',       
        'rev_date',    
        'comment',     
        'product_id',  
        'user_id',     
    ];

    /**
     * Define the relationship with the Product model.
     * Each review belongs to a specific product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Define the relationship with the User model.
     * Each review is written by a specific user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define the relationship with the ReviewVote model.
     * A review can have many votes.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class, 'review_id', 'review_id');
    }
}
