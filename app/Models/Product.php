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

    public function rules() {
        return [
            'file' => 'required|file|mimes:json,xml',
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'file.mimes' => 'O arquivo deve ser do tipo JSON ou XML',
        ];
    }
}
