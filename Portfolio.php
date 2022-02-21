<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image', 'status', 'service_id','client_id'];
   // protected $table = 'portfolios';
   public function service()
   {
       return $this->belongsto(Service::class);
   }
   public function client()
   {
       return $this->belongsTo(Client::class);
   }

}
