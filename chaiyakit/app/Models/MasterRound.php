<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $master_round_id
 * @property string $name
 * @property string $round_time
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MasterRound extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_round';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_round_id';

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
    protected $fillable = ['name', 'round_time', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];

}
