<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];
    public $incrementing = false;

    public function rules() {
        return [
            'id' => 'required|integer',
            'name' => 'required|unique:products,name,',
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'id.integer' => 'O id do produto precisa ser do tipo inteiro',
            'name.unique' => 'O name do produto já existe',
        ];
    }
}
