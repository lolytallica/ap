<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/21/14
 * Time: 12:06 PM
 */
class Merchantagreement extends Eloquent {

    protected $table = 'merchantagreement';

    protected $fillable = array('name', 'description', 'activated','name','merchant_id','bankaccount_id');

    public $timestamps = true;

    public function merchant()
    {
        return $this->belongsTo('Merchant');
    }

    public function Mainvoice()
    {
        return $this->belongsTo('mainvoice','merchantagreement_id');
    }

    public function bankaccounts()
    {
        return $this->hasOne('Bankaccount');
    }

    ////////////////////////////////////////////////

    public function bankaccount()
    {
       return Bankaccount::where('id','=',$this->bankaccount_id)->first();

    }


    public function map()
    {
        return $this->belongsToMany('Map', 'ma_map')->withPivot('map_value', 'status_id', 'merchantagreement_id', 'map_id');
    }

    public function parameters()
    {
        return DB::table('ma_map')->join('map','map.id','=','ma_map.map_id')->where('ma_map.merchantagreement_id','=',$this->id)->get();
    }

    public function paramval($param)
    {
        return DB::table('map')->join('ma_map','map.id','=','ma_map.map_id')
            ->where('ma_map.merchantagreement_id','=',$this->id)
            ->where('map.parameter','=',$param)
            ->where('ma_map.status_id','=','1')  //Parameter with status active
            ->first();
    }

    public function currency()
    {
        return DB::table('currency')->where('currency.id','=',$this->paramval('processcurrency'))->get();
    }

}