<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];
    public $incrementing = false;

    public function product_tags() {
        return $this->hasMany(ProductTag::class);
    }
}
