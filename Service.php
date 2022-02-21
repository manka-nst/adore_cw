<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'icon', 'description', 'status'];
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }
}
