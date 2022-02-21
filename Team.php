<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'link', 'description', 'job', 'status', 'image', 'facebook_icon', 'twitter_icon', 'linkedIn'];
}
