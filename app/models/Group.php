<?php

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroupModel;

class Group extends SentryGroupModel {

    public function hasGroups()
    {

        $sql = "WITH RECURSIVE nodes_cte(id, name, manager_id, depth, path) AS (
                SELECT tn.id, tn.name, tn.manager_id, 1::INT AS depth, tn.id::TEXT AS path FROM groups AS tn WHERE tn.id = ".$this->id."
                UNION ALL
                SELECT c.id, c.name, c.manager_id, p.depth + 1 AS depth, (p.path || '->' || c.id::TEXT) FROM nodes_cte AS p, groups AS c WHERE c.manager_id = p.id
                )
                SELECT * FROM nodes_cte AS n";


        $groups = DB::select($sql);

        //$groups = DB::table('groups')->where('manager_id','=',$id)->get();



        return $groups;
    }

}
