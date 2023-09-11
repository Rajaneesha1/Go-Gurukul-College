<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'percentage', 'certificate_issued', 'correctAnswers', 'wrongAnswers', 'user_flag'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function isCertificateIssued()
    {
        return $this->certificate_issued;
    }
}
