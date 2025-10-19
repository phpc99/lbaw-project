<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category'; 
    protected $primaryKey = 'category_id';
    public $incrementing = true; 
    public $timestamps = false; 

    protected $fillable = [
        'name',
    ];  

    /**
     * Many-to-Many: A category can have many products.
     * This relationship is defined via the 'product_category' pivot table.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,        
            'product_category',     
            'category_id',          
            'product_id'            
        );
    }
}
