<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $product_return_claim_item_id
 * @property integer $product_return_claim_id
 * @property integer $order_item_id
 * @property int $qty
 */
class ProductReturnClaimItem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'product_return_claim_item';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'product_return_claim_item_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'bigInteger';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['product_return_claim_id', 'order_item_id', 'qty'];



}
