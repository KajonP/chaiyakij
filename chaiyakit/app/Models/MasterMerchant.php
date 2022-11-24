<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $master_merchant_id
 * @property string $name_merchant
 * @property string $name_department
 * @property string $tax_number
 * @property string $phone_number
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property string $link_google_map
 * @property string $remark
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property MasterMerchantPrice[] $masterMerchantPrices
 */
class MasterMerchant extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_merchant';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'master_merchant_id';

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
    protected $fillable = ['name_merchant', 'name_department', 'tax_number', 'phone_number', 'address', 'latitude', 'longitude', 'link_google_map', 'remark', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by','phone_number2','phone_number3','phone_number4','fax'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function masterMerchantPrices()
    {
        return $this->hasMany('App\Models\MasterMerchantPrice', null, 'master_merchant_id');
    }

    // master merchant use type
    public function usedData()
    {
        return $this->hasMany('App\Models\Order', 'merchant_id');
    }
    public function getFormattedCreatedDate()
    {

        return Tools::convertdDate($this->attributes['created_date']);

    }
    public function getFormattedUpdatedDate()
    {

        return Tools::convertdDate($this->attributes['updated_date']);

    }
    public function getFormattedDeletedDate()
    {

        return Tools::convertdDate($this->attributes['deleted_date']);

    }
}
