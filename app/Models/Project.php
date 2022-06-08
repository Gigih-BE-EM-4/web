<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'images',
        'description',
        'active',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function projectRoles()
    {
        return $this->hasMany('App\Models\ProjectRole');
    }
    public function projectMembers()
    {
        return $this->hasMany('App\Models\ProjectMember');
    }
    public function applies()
    {
        return $this->hasMany('App\Models\Apply');
    } 
}
