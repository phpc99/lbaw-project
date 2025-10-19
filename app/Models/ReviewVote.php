<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewVote extends Model
{
    use HasFactory;

    protected $table = 'review_vote'; 
    protected $primaryKey = 'review_vote_id'; 
    public $timestamps = false; 

    protected $fillable = [
        'vote',       
        'user_id',    
        'review_id',  
    ];

    /**
     * Define the relationship with the Review model.
     * Each vote is associated with a specific review.
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id', 'review_id');
    }

    /**
     * Define the relationship with the User model.
     * Each vote is cast by a specific user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
