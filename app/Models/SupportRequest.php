<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $table = 'support_requests';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reply','status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    static function getTopRequests($limit=6) {
        return self::select('support_requests.*', 'users.fullname')
                ->join('users', 'users.user_id', '=', 'support_requests.user_id')
                ->whereIn('support_requests.status', [0, 1])
                ->orderBy('support_requests.date_created', 'desc')
                ->limit($limit)
                ->get();
    }
}
