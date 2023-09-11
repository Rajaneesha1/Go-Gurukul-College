<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'subcategories';

    protected $fillable = ['name', 'content', 'image'];

    protected $casts = [
        'image' => 'array',
    ];
}
