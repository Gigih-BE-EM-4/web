<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quota',
        'description',
        'project_id',
        'extra_question',
    ];

    public function projecs()
    {
        return $this->hasMany('App\Models\Project');
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
