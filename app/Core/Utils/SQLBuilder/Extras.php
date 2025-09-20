<?php

namespace App\Core\Utils\SQLBuilder;


class Extras {


    public static function groupBy($groupBy) {
        return " GROUP BY $groupBy";
    }


    public static function orderBy($orderBy, $sortOrder) {
        return " ORDER BY $orderBy $sortOrder";
    }


    public static function limit($limit) {
        return " LIMIT $limit";
    }


    public static function offset($offset) {
        return " OFFSET $offset";
    }
}
