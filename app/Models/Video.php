<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;

class Video extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'videos';
    protected $fillable = ['title', 'link', 'image', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_video', 'video_id', 'admin_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'video_id');
    }

}









