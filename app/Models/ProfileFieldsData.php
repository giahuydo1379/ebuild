<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileFieldsData extends Model
{
    protected $table = 'order_profile_fields_data';

    protected $primaryKey = 'object_id';//'object_id', 'object_type', 'field_id'
    public $incrementing = false;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['object_id', 'object_type', 'field_id', 'value', 'extra'];
}
