<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/21/14
 * Time: 12:33 PM
 */
class Transactionorder extends Eloquent {

    protected $table = 'order';

    public $timestamps = true;


public function byMerchant($merchantID, $date_from, $date_to)
{
    return $this->join('transaction','order.transaction_id','=','transaction.id')
        ->where('transaction.merchant_id','=',$merchantID)
        ->where('order.datetimecreated'<= $date_from)
        ->where('order.datetimecreated'>= $date_to)
        ->get();
}

    public function byMerchantagreement($merchantagreementID, $date_from, $date_to)
    {
        return $this->join('transaction','order.transaction_id','=','transaction.id')
            ->join('merchantagreement','merchantagreement.merchant_id','=','transaction.merchant_id')
            ->where('merchantagreement.id','=',$merchantagreementID)
            ->where('order.datetimecreated'<= $date_from)
            ->where('order.datetimecreated'>= $date_to)
            ->get();
    }


}
