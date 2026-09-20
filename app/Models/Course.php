<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'name',
        'code',
        'duration',
        'total_seats',
        'available_seats',
        'status',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
