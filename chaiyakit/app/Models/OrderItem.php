<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $order_item_id
 * @property integer $order_id
 * @property integer $master_product_main_id
 * @property integer $master_product_type_id
 * @property integer $master_product_size_id
 * @property float $price
 * @property int $qty_all
 * @property int $qty_pending
 */
class OrderItem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'order_item';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'order_item_id';

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
   
}
