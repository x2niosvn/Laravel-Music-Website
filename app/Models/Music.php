<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable = ['title', 'artist', 'genre', 'file_path', 'img_path', 'user_id'];

    public function user()
    {
        //Gắn user_id tương ứng với cột id trong bảng user
        return $this->belongsTo(User::class);
    }
}
