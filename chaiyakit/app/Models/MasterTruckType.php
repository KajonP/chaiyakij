<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $master_truck_type_id
 * @property string $type
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MasterTruckType extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_truck_type';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_truck_type_id';

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
    protected $fillable = ['type', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];

    // master truck use type
    public function usedData()
    {
        return $this->hasMany('App\Models\MasterTruck', 'master_truck_type_id');
    }
}
