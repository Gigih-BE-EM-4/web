<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'project_role_id',
        'user_id',
        'certificate',
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function projectRole()
    {
        return $this->belongsTo('App\Models\ProjectRole');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
