<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureVariants extends Model
{
    protected $table = 'product_features_variants';

    protected $primaryKey = 'id';

//    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['feature_id', 'position', 'name', 'description', 'status', 'from', 'to',
        'page_title', 'meta_keywords', 'meta_description'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getFiltersForJobs($params) {
        $objects = self::select('product_features_variants.*');

        $objects->whereIn('product_features_variants.id', function ($q) use ($params) {
            $q->select('fv.variant_id')
                ->from('product_features_values as fv')
                ->whereExists(function ($query) use ($params) {
                    $query->select(\DB::raw(1))
                        ->from('ws_jobs as j')
                        ->whereRaw('j.id = fv.job_id')
                        ->where('j.status', 1);
                })
                ->groupBy('fv.variant_id');
        });

        $objects->orderBy('product_features_variants.position', 'ASC');

        $objects = $objects->get()->toArray();

        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['feature_id']][] = $item;
        }

        return $rs;
    }
}