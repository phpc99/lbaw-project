<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model {
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'photo',
        'quantity',
        'rating',
        'description',
        'foot_print',
        'publication_date',
    ];

    /**
     * One-to-Many: A product can have many reviews.
     */
    public function reviews(){
        return $this->hasMany(Review::class, 'product_id');
    }
    /**
     * One-to-Many: A product can belong to one purchase.
     */
    /*public function purchase(): BelongsTo {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id');
    }*/
    /**
     * Many-to-Many: A product can belong to multiple categories.
     */
    public function categories(): BelongsToMany {
        return $this->belongsToMany(
            Category::class,
            'product_category',
            'product_id',
            'category_id'
        );
    }
    /**
     * Many-to-Many: A product can appear in many users' carts.
     */
    public function cartUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'cart',
            'product_id',
            'user_id'
        )->withPivot('quantity', 'total');
    }
    /**
     * Many-to-Many: A product can appear in many users' wishlists.
     */
    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'wishlist',
            'product_id',
            'user_id'
        );
    }
    /**
     * Many-to-Many: A product can be part of many purchases.
     */
    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(
            Purchase::class,
            'purchase_product',
            'product_id',
            'purchase_id'
        )->withPivot('quantity');
    }

    public static function searchProducts($query) 
    {
        //return static::whereRaw("search_vector @@ plainto_tsquery('english', ?)", [$query])->get();
        return static::selectRaw("*, ts_rank(search_vector, plainto_tsquery('english', ?)) AS rank", [$query])
            ->whereRaw("search_vector @@ plainto_tsquery('english', ?)", [$query])
            ->orderBy('rank', 'desc')
            ->get();
    }


}