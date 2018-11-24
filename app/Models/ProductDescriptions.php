<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/7/2018
 * Time: 10:44 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProductDescriptions extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'product_descriptions';

    protected $primaryKey = 'product_id';//['product_id', 'lang_code'];

    public $incrementing = false;

    protected $auditExclude = [
        'short_description', 'full_description', 'specifications'
    ];

    protected $fillable = [
        'product_id',
        'lang_code',
        'product',
        'shortname',
        'short_description',
        'full_description',
        'meta_keywords',
        'meta_description',
        'page_title',
        'search_words',
        'specifications',
        'alias'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}