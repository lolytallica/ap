<?php


class Reportsearch extends Eloquent {

    protected $table = 'report_search';

    protected $fillable = array('user_id', 'name');

    public $timestamps = true;


    public function resultfields()
    {
        return $this->belongsToMany('Resultfields', 'report_result_fields', 'reporttype_id');
    }

    public function reporttype()
    {
        return $this->belongsToMany('Reporttype', 'report_search_fields', 'reporttype_id');
    }

    public function searchfields()
    {
        return $this->belongsToMany('Searchfields', 'report_search_fields', 'reporttype_id');
    }

            //////////////////////////////////////////////
            public function additionalfields()
            {
                $additionalfields = Searchfields::join('report_search_fields','report_search_fields.searchfields_id','=','search_fields.id')
                    ->where('search_fields.additional','=','1')
                    ->where('report_search_fields.reportsearch_id','=', $this->id)
                    ->get();

                return $additionalfields;
            }

            public function formfields()
            {
                $formfields = Searchfields::join('report_search_fields','report_search_fields.searchfields_id','=','search_fields.id')
                    ->where('search_fields.additional','=','0')
                    ->where('report_search_fields.reportsearch_id','=', $this->id)
                    ->get();

                return $formfields;
            }
            //////////////////////////////////////////////



    public function reptype()
    {
        $rep = Reporttype::join('report_search_fields','reporttype_id','=','search_types.id')->where('report_search_fields.reportsearch_id','=',$this->id)->first();

        return $rep->searchtype;
    }



}
