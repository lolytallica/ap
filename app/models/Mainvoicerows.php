<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 4:56 PM
 */
class Mainvoicerows extends Eloquent {

    protected $table = 'mainvoicerows';

    protected $fillable = array('mainvoice_id', 'description', 'amount', 'user_id', 'type', 'custom_reason', 'note', 'created_at', 'updated_at');

    public $timestamps = true;

    public function mainvoice()
    {
        return $this->belongsTo('Mainvoice');
    }

}
