<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterMerchant;

/**
 * @property integer $order_id
 * @property integer $merchant_id
 * @property string $order_number
 * @property string $staus_send
 * @property string $status_order
 * @property string $status_vat
 * @property string $order_type
 * @property float $price_all
 * @property float $vat
 * @property float $weight
 * @property int $qty_all
 * @property int $qty_pending
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class Order extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

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
    public function getFormattedCreatedDate()
    {

        return Tools::convertdDate($this->attributes['created_date']);

    }
    public function getOrderDate()
    {

        return Tools::convertdDateOnly($this->attributes['created_date']);

    }
    public function getFormattedUpdatedDate()
    {

        return Tools::convertdDate($this->attributes['updated_date']);

    }
    public function getFormattedDeletedDate()
    {

        return Tools::convertdDate($this->attributes['deleted_date']);

    }

    public function getCustomer()
    {
        return $this->belongsTo(MasterMerchant::class,'merchant_id');
    }
}
