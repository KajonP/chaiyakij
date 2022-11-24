<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $master_vat_id
 * @property float $vat
 * @property string $updated_date
 * @property integer $updated_by
 */
class MasterVat extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_vat';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_vat_id';

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
    // protected $fillable = ['vat', 'updated_date', 'updated_by'];

    public function getFormattedUpdatedDate()
    {
       
        return Tools::convertdDate($this->attributes['updated_date']);
     
    }
}
