<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 3/19/14
 * Time: 10:42 AM
 */
class Merchant extends Eloquent {

    protected $table = 'merchant';

    protected $fillable = array('pspaccount_id', 'merchant', 'merchantemail', 'abbreviation', 'aguid', 'active');

    public $timestamps = true;

    public function merchantagreement()
    {
        return $this->hasMany('Merchantagreement');
    }


    public function bankaccount()
    {
        return $this->hasMany('Bankaccount');
    }

    ////////////////////////////////////////////////////

    public function merchantagreements()
    {
        return DB::table('merchantagreement')->where('merchant_id','=',$this->id)->get();
    }

    public function mainvoice()
    {
        return $this->hasMany('Mainvoice');
    }

  /*  public function transactions($date_from, $date_to)
    {
        return Transactionorder::join('transaction','transaction.id','=','transaction_id')
            ->where('order.datetimecreated','<=', $date_from)
            ->where('order.datetimecreated','>=', $date_to)
            ->where('merchant_id','=',$this->id)
            ->groupBy('transaction.merchant_id')
            ->count();
    }*/


}
