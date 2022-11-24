<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $notification_log_id
 * @property integer $notification_id
 * @property integer $read_by
 * @property string $created_date
 */
class NotificationLog extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'notification_log';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'notification_log_id';

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
    protected $fillable = ['notification_id', 'read_by', 'created_date'];
    public function getFormattedCreatedDate()
    {
       
        return Tools::convertdDate($this->attributes['created_date']);
     
    }
}
