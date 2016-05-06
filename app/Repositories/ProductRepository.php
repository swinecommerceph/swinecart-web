<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Breeder;

class ProductRepository
{
    /**
     * Get all of the products for the Customer
     *
     * @return Collection
     */
    public function forCustomer()
    {
        return Product::all();
    }

    /**
     * Get all of the products of a given Breeder
     *
     * @return Collection
     */
    public function forBreeder(Breeder $breeder)
    {
        // return Product::all();
    }
}
