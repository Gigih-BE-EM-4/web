<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'project_id',
    'project_role_id',
    'cv',
    'extra_answer',
  ];
  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
  public function projectRole()
  {
    return $this->belongsTo(ProjectRole::class, 'project_role_id', 'id');
  }
  public function project()
  {
    return $this->belongsTo('App\Models\Project');
  }
}
