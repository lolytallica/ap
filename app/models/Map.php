<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/24/14
 * Time: 1:06 PM
 */
class Map extends Eloquent {

    protected $table = 'map';

    public $timestamps = true;

    public function merchantagreement()
    {
        return $this->belongsToMany('Merchantagreement', 'ma_map')->with_pivot('parameter', 'merchantagreement_id', 'map_id');
    }

    ///get statuses for a specific merchant agreement $id
    public function mapstatus($id)
    {
        return $this->join('ma_map','map.id','=','ma_map.map_id')->join('map_status','map_status.id','=','ma_map.status_id')->where('ma_map.merchantagreement_id','=',$id)->get();
    }

    //Returns Current Statuses
    public function currentstatus($id)
    {
        $sql = "SELECT DISTINCT ma_map.map_id, map.parameter, map.extension, map.description, ma_map.status_id, ma_map.map_value, tbl.lastcreated, map_status.status
                FROM map, map_status, ma_map, (SELECT ma_map.map_id AS max_map_id, MAX(created_at) as lastcreated FROM ma_map WHERE merchantagreement_id = ".$id." GROUP BY ma_map.map_id) AS tbl
                WHERE ma_map.merchantagreement_id = ".$id." AND ma_map.created_at = tbl.lastcreated AND ma_map.map_id = tbl.max_map_id  AND map.id = ma_map.map_id AND ma_map.status_id = map_status.id
                ORDER BY ma_map.map_id ";

        return DB::select(DB::raw($sql));
    }


}
