<?php

namespace App\Models;
use App\Models\Tools;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_login
 */
class AdminUsers extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'bigInteger';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'email_verified_at', 'created_at', 'updated_at', 'last_login'];
    protected $hidden = [
        'password','remember_token'
    ];
    public function getFormattedCreatedDate()
    {
       
        return Tools::convertdDate($this->attributes['created_at']);
     
    }
    public function getFormattedUpdatedDate()
    {
       
        return Tools::convertdDate($this->attributes['updated_at']);
     
    }
}
