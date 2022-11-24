<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $product_return_claim_id
 * @property integer $order_id
 * @property string $remark
 * @property string $type
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class ProductReturnClaim extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'product_return_claim';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'product_return_claim_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'bigInteger';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * @var array
     */
    protected $fillable = ['order_id', 'remark', 'type', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];
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
