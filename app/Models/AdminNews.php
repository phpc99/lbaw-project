<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNews extends Model
{
    use HasFactory;
    protected $table = 'admin_news';
    public $primary_key = 'news_id';
    protected $fillable = ['title', 'content', 'users_id', 'created_at', 'category'];
    public $timestamps = false;

}
