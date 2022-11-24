<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $master_truck_id
 * @property integer $master_truck_type_id
 * @property float $weight
 * @property string $license_plate
 * @property string $date_vat_expire
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MasterTruck extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_truck';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_truck_id';

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
    protected $fillable = ['master_truck_type_id', 'weight', 'license_plate', 'date_vat_expire', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];
}
