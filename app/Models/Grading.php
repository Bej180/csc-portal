<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'grading_system'];


    function store(string $name, array $gradings) {
        return Grading::create([
            'name' => $name,
            'grading_system' => json_encode($gradings)
        ]);
    }

    public function results() {
        return $this->hasMany(Result::class, 'grading_id');
    }
}
