<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/17/14
 * Time: 10:56 AM
 */
class Grouplevel extends Eloquent {

    public function levels()
    {
        $sql = "WITH RECURSIVE nodes_cte(id, name, manager_id, depth, path) AS (
                SELECT tn.id, tn.name, tn.manager_id, 1::INT AS depth, tn.id::TEXT AS path FROM groups AS tn WHERE tn.id = 1
                UNION ALL
                SELECT c.id, c.name, c.manager_id, p.depth + 1 AS depth, (p.path || '->' || c.id::TEXT) FROM nodes_cte AS p, groups AS c WHERE c.manager_id = p.id
                )
                SELECT * FROM nodes_cte AS n;";

        $results = DB::select($sql);

        return $results;
    }

}
