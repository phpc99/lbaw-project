<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address'; 
    protected $primaryKey = 'address_id'; 
    public $timestamps = false; 

    protected $fillable = [
        'street',       
        'city',         
        'country',      
        'postal_code',  
        'user_id',     
    ];

    /**
     * Define the relationship with the User model.
     * Each address belongs to a specific user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
