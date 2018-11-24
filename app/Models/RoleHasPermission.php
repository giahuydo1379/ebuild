<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
//    protected $primaryKey = 'permission_id';
    protected $fillable = ['permission_id', 'role_id', 'user_created', 'user_modified'];
}
