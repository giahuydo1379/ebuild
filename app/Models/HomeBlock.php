<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HomeBlock extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'home_blocks';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['block', 'name', 'link', 'ordering', 'status', 'user_created', 'user_modified',
        'sort', 'content'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
