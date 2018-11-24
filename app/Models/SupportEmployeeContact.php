<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportEmployeeContact extends Model
{
    protected $table = 'support_employees_contacts';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id','name','email','phone','type','content','reply','status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
