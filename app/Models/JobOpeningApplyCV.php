<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpeningApplyCV extends Model
{
    protected $table = 'job_opening_apply_cv';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['position', 'alias', 'job_category_id', 'keywords', 'province_id', 'description', 'request',
        'other_information', 'status', 'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
