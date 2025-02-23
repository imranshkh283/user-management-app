<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_images extends Model
{
    use HasFactory;
    protected $table = 'user_img';
    protected $fillable = ['image_name'];
}
