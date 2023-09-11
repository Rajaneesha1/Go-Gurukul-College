<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'option1', 'option2', 'option3', 'option4', 'correct_answer', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
