<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/7/2018
 * Time: 10:44 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductFiles extends Model
{
    protected $table = 'product_files';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pid',
        'caption',
        'caption_en',
        'file_type',
        'file_name',
        'image_url',
        'is_main',
        'sync_data'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getDataByProductId($product_id){

        $query = self::where('pid', $product_id);

        $rs = $query->get();

        $data = [];
        if($rs){
            $rs = $rs->toArray();
            foreach($rs as $item){
                if($item['is_main']){
                    $data['is_main'] = $item;
                }else{
                    $data['details'][] = $item;
                }
            }
        }

        return $data;
    }

    public static function getImagesByProductIds($ids) {
        $objects = self::select('product_files.*')
            ->whereIn('product_files.pid', $ids)
            ->where('product_files.is_main', 1);

        $objects = $objects->get()->toArray();

        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['pid']] = $item;
        }

        return $rs;
    }

}