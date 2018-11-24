<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 1/30/2018
 * Time: 5:09 PM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CategoryDescriptions extends Model
{
    protected $table = 'category_descriptions';

    protected $primaryKey = 'category_id';

    protected $fillable = ['category_id','lang_code','category','description','meta_keywords','meta_description','page_title','age_warning_message','sync_data','alias'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function primary(){
        return ['lang_code'];
    }
}