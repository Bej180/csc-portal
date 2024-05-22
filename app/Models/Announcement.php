<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'target',
        'user_id'
    ];


    public function announcer() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
