<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

//use Attribute;
//use Faker\Provider\ar_EG\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;


// Added to define Eloquent relationships.
//use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    //use HasApiTokens
    use HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'users'; 
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'points',
        'permissions',
        'picture',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        //'password' => 'hashed',
        'points' => 'float',
    ];

    public function setPermission($value) {
        $valid_permissions = ['customer', 'admin', 'reviewAuthor'];
        if (!in_array($value, $valid_permissions)) {
            throw new \InvalidArgumentException("Invalid permission: $value");
        }
        $this->attributes['permissions'] = $value;
    }

    /**
     * Define a One-to-One relationship with Address.
     */
    public function address(): HasMany {
        return $this->hasMany(Address::class, 'user_id', 'user_id');
    }
    /**
     * Define a One-to-Many relationship with Purchases.
     */
    public function purchases(): HasMany {
        return $this->hasMany(Purchase::class, 'user_id', 'user_id');
    }
    /**
     * Define a One-to-Many relationship with Reviews.
     */
    public function reviews(): HasMany {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }
    /**
     * Define a One-to-Many relationship with ReviewVote.
     */
    public function reviewVotes(): HasMany {
        return $this->hasMany(ReviewVote::class, 'user_id', 'user_id');
    }
    /**
     * Define a Many-to-Many relationship with Products (for Cart).
     */
    public function cartProducts(): BelongsToMany {
        return $this->belongsToMany(
            Product::class,          // Related model
            'Cart',                  // Pivot table
            'user_id',               // Foreign key on the pivot table for this model
            'product_id'             // Foreign key on the pivot table for the related model
        )->withPivot('quantity', 'total');   // Include 'quantity' from the pivot table.
    }
     /**
     * Define a Many-to-Many relationship with Products (for Wishlist).
     */
    public function wishlistProducts(): BelongsToMany {
        return $this->belongsToMany(
            Product::class,          // Related model
            'Wishlist',              // Pivot table
            'user_id',               // Foreign key on the pivot table for this model
            'product_id'             // Foreign key on the pivot table for the related model
        );
    }
    
    public function isAdmin()
    {
        return $this->permissions === 'admin';
    }

    public function isCustomer()
    {
        return $this->permissions === 'customer';
    }
}
    
