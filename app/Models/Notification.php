<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications'; 
    protected $primaryKey = 'notification_id'; 
    //public $timestamps = false;  we need to know when it was created

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array', 
    ];

    public function getMessage() 
    {    
        switch ($this->type) {
            case 'new_product':
                return "A new product '{$this->data['product_name']}' has been added for \${$this->data['product_price']}. Go check it out!";
            case 'price_change':
                return "The price of '{$this->data['product_name']}', which is in your shopping cart, has changed from \${$this->data['old_price']} to \${$this->data['new_price']}. Ensure you are still interested in purchasing this product.";
            case 'OrderStatusUpdated':
                return "The status of your order #{$this->data['order_id']} has been updated to '{$this->data['status']}'.";
            default:
                return "Something went wrong. This notification has no type.";
        }
    }

}
