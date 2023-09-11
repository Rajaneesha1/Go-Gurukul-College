<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'comments';
    protected $fillable = ['content', 'video_id', 'user_id']; // Add 'user_id' to the fillable fields

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Define the relationship with the User model
    }

    public function replies()
{
    return $this->hasMany(Comment::class, 'parent_id');
}

public function parent()
{
    return $this->belongsTo(Comment::class, 'parent_id');
}
}





