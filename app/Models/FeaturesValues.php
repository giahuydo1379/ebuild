<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturesValues extends Model
{
    protected $table = 'product_features_values';

    protected $primaryKey = 'variant_id';

    public $incrementing = false;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['feature_id', 'product_id', 'variant_id','is_show_frontend'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getDetail($product_id){
        $query = self::select('product_features_values.feature_id',
            'product_features_values.variant_id', 'product_features_variants.name as variant','product_features.name_vi as feature_name')
            ->where('product_features_values.product_id', $product_id)
            ->join('product_features', function($join1) {
                $join1->on('product_features.id','=','product_features_values.feature_id');
                $join1->where('product_features.status', 1);
            })
            ->join('product_features_variants', function($join2) {
                $join2->on('product_features_variants.id','=','product_features_values.variant_id');
            })
            ->where('product_features_values.is_show_frontend',1)
            ->orderBy('product_features.position', 'asc')
            ->orderBy('product_features_variants.position', 'asc');
        $result = $query->get()->toArray();
        
        if(empty($result)) return [];

        $tmp = [];
        foreach($result as $item){
            $tmp[$item['feature_id']]['name']   = $item['feature_name'];
            $tmp[$item['feature_id']]['data'][] = $item;
        }
        
        return $tmp;
    }
}
