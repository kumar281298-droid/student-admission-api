<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'email',
        'phone',
        'logo',
        'status',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'COLLEGE_ADMIN');
    }
}
