<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasRole extends Model
{
    protected $table = 'admin_user_has_roles';

    protected $fillable = ['role_id', 'user_id'];

    public $timestamps = false;
}
