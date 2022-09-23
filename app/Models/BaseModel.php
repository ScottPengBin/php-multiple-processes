<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use \Illuminate\Database\Eloquent\Builder;
use PDO;


class BaseModel extends Model
{

    /**
     * @var MySqlConnection
     */
    public static $mysqlConn;

    /**
     * @var PDO
     */
    public static $conn;

    /**
     * @var
     */
    public static $query;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!self::$conn) {
            self::$conn = new PDO('mysql:dbname=jin_cheng;host=172.20.224.1;port=3307', 'root', 'root');
        }
        if (!self::$mysqlConn) {
            self::$mysqlConn = new MySqlConnection(self::$conn, '', 'jc_');
        }
    }

    public function getConnection(): \Illuminate\Database\Connection
    {
        return self::$mysqlConn;
    }

    public function newQuery(): Builder
    {
        if (self::$query) {
            return self::$query;
        }

        $this->setConnection('mysql');


        $query = new QueryBuilder(self::$mysqlConn);

        $builder = new Builder($query);

        $builder = $this->registerGlobalScopes($builder);

        static::$query = $builder->setModel($this);

        return $builder;
    }
}