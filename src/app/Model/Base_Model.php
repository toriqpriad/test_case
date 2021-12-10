<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class Base_Model extends Model
{
    public $connection;
    public $timestamp;
    public $DB;
    public $raw;
    public $table_user        = "users";
    public $table_merchant    = "merchants";
    public $table_outlets     = "outlets";
    public $table_transaction = "transactions";

    public function __construct($table_name = FALSE, $alias = FALSE)
    {
        if ($table_name) {
            if ($alias == TRUE) {
                $table_name = $table_name . " as " . $alias;
            }
            $this->connection = DB::table($table_name);
        }
        $this->DB        = new DB;
        $this->timestamp = date("Y-m-d H:i:s");
    }

    public function do_raw($sql)
    {
        return DB::raw($sql);
    }

    public function do_sql($sql)
    {
        return DB::select($sql);
    }


    public function query_dd($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            $binding = addslashes($binding);
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

}
