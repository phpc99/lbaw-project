<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function create(User $user)
    {
        return $user->isAdmin(); 
    }

    public function update(User $user, Product $product)
    {
        return $user->isAdmin(); 
    }

    public function delete(User $user, Product $product)
    {
        return $user->isAdmin(); 
    }

    public function addReview(User $user, Product $product)
    {
        return $user->isCustomer();
    }

    public function updateReview(User $user, Product $product)
    {
        return $user->reviews()->where('product_id', $product->id)->exists(); 
    }

    /*public function checkProductName(User $user)
    {
        return $user->isAdmin() || $user->isCustomer();
    }*/
}
