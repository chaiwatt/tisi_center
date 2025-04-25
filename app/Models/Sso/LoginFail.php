<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;

class LoginFail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sso_login_fails';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'username',
                            'ip_address',
                            'login_at'
                        ];

    public $timestamps = false;

}
