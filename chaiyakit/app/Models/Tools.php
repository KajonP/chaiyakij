<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    public static function convertdDate($date)
    {
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        return $dt->addYears(543)->format('d/m/Y H:i:s');
     
    }
    public static function convertdDateOnly($date)
    {
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        return $dt->addYears(543)->format('d/m/Y');
     
    }
    public static function convertdWeight($weight)
    {
        if($weight < 1000)
        return ['number'=>number_format($weight,2),'unit'=>'กรัม'];
        else
        return ['number'=>number_format($weight/1000,2),'unit'=>'กิโลกรัม'];
     
    }
}
