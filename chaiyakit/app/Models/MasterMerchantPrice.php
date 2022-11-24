<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $master_merchant_price_id
 * @property integer $master_merchant_id
 * @property integer $master_product_main_id
 * @property integer $master_product_type_id
 * @property integer $master_product_size_id
 * @property float $price
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property MasterMerchant $masterMerchant
 */
class MasterMerchantPrice extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_merchant_price';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_merchant_price_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'bigInteger';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';
    
    /**
     * @var array
     */
    protected $fillable = ['master_merchant_id', 'master_product_main_id', 'master_product_type_id', 'master_product_size_id', 'price', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masterMerchant()
    {
        return $this->belongsTo('App\Models\MasterMerchant', null, 'master_merchant_id');
    }
}
