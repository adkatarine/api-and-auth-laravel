<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function product_tags() {
        return $this->hasMany(ProductTag::class);
    }

    public function rules() {
        return [
            'name' => 'required|unique:tags,name,',
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'name.unique' => 'O name da tag já existe',
        ];
    }
}
