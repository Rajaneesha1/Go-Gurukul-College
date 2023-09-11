<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Http\Controllers\Controller;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'image'];

    protected $casts = [
        'image' => 'array',
    ];

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
