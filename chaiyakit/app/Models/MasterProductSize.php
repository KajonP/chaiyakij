<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $master_product_size_id
 * @property integer $master_product_main_id
 * @property integer $master_product_type_id
 * @property string $name
 * @property float $weight
 * @property string $created_date
 * @property string $updated_date
 * @property string $deleted_date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class MasterProductSize extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'master_product_size';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'master_product_size_id';

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
    // protected $fillable = ['master_product_main_id', 'master_product_type_id', 'name', 'weight', 'created_date', 'updated_date', 'deleted_date', 'created_by', 'updated_by', 'deleted_by'];
    public function getFormattedSizeAttribute()
    {
        
        return sprintf('%g',$this->attributes['name']);
    }
     public function getFormattedWeight()
    {   $size=$this->attributes['weight'];
        if ($size < 1000) {
            return $size . ' กรัม';
        }
        else
        {
            if ($size >= 1000 && $size < 1000 * 1000) {
                return sprintf('%01.2f', $size / 1000.0) . ' กิโลกรัม';
            } else {
                return sprintf('%01.2f', $size / (1000 * 1000)) . ' ตัน';
            }
        }
        
    }
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
