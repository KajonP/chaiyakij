<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $order_delivery_item_id
 * @property integer $order_delivery_id
 * @property integer $order_item_id
 * @property int $qty
 */
class OrderDeliveryItem extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'order_delivery_item';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'order_delivery_item_id';

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
    protected $fillable = ['order_delivery_id', 'order_item_id', 'qty'];

}
