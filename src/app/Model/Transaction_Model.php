<?php

namespace App\Model;

use App\Model\Base_Model;

class Transaction_Model extends Base_Model
{

    public function __construct($table_name = FALSE, $alias = FALSE)
    {
        parent::__construct($table_name, $alias);
    }

    public function do_connection($alias = FALSE)
    {
        $db    = FALSE;
        $table = $this->table_transaction;
        if ($alias == TRUE) {
            $db = $this->DB::table($table . ' as ' . $alias);
        } else {
            $db = $this->DB::table($table);
        }
        return $db;
    }
}
