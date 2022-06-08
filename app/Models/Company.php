<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'profile',
        'bio',
        'address',
        'category',
        'email',
        'contact',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

}
