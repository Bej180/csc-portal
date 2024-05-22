<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dean extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'gender',
        'image',
        'id',
        'staff_id'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }



    public function picture() {
        return $this->user->picture();
    }


    
}
