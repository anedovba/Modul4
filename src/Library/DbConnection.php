<?php
namespace Library;
class DbConnection
{
    private $pdo;

    public function __construct(Config $config)
    {
        $dsn = 'mysql: host=' . $config->dbhost . '; dbname='. $config->dbname.'; charset='. $config->dbcharset;
        $this->pdo = new \PDO($dsn, $config->dbuser, $config->dbpass);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    /**
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
}