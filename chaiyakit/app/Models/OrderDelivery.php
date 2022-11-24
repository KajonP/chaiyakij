<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $order_delivery_id
 * @property integer $order_id
 * @property integer $product_return_claim_id
 * @property string $remark
 * @property string $type
 * @property string $status
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class OrderDelivery extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'order_delivery';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'order_delivery_id';

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
    // protected $fillable = ['order_id', 'product_return_claim_id', 'remark', 'type', 'status', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];
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
