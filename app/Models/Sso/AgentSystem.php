<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;

class AgentSystem extends Model
{
    protected $table = 'sso_agent_systems';

    protected $primaryKey = 'id';

    protected $fillable = ['setting_systems_id', 'sso_agent_id'];
}
