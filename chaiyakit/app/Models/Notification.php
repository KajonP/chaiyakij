<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $notification_id
 * @property string $name
 * @property string $link
 * @property string $type
 * @property string $created_date
 */
class Notification extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'notification';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'notification_id';

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
    protected $fillable = ['name', 'link', 'type', 'created_date'];
    public function getFormattedCreatedDate()
    {
       
        return Tools::convertdDate($this->attributes['created_date']);
     
    }
}
