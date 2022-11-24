<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $truck_schedule_id
 * @property integer $order_delivery_id
 * @property integer $master_round_id
 * @property integer $master_truck_id
 * @property string $date_schedule
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class TruckSchedule extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'truck_schedule';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'truck_schedule_id';

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
    protected $fillable = ['order_delivery_id', 'master_round_id', 'master_truck_id', 'date_schedule', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];

}
