<?php

namespace Nexus\Database;

class DBPdo implements DBInterface
{
    private $pdo;

    public function connect($host, $username, $password, $database, $port = 3306)
    {
        $host = trim((string)$host);
        $port = intval($port) ?: 3306;
        $database = trim((string)$database);
        // 支持 host:port 写法与 unix socket 写法（/tmp/mysql.sock）
        if (str_starts_with($host, '/')) {
            $dsn = sprintf('mysql:unix_socket=%s;dbname=%s;charset=utf8mb4', $host, $database);
        } else {
            if (str_contains($host, ':')) {
                [$host, $hostPort] = explode(':', $host, 2);
                if (intval($hostPort)) {
                    $port = intval($hostPort);
                }
            }
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);
        }
        $this->pdo = new \PDO($dsn, $username, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_BOTH,
            \PDO::ATTR_EMULATE_PREPARES => true,
        ]);
        $this->pdo->exec("SET collation_connection = 'utf8mb4_unicode_ci'");
        $this->pdo->exec("SET sql_mode=''");
        $this->pdo->exec("SET time_zone='" . date('P') . "'");
        return $this->pdo;
    }

    public function query(string $sql)
    {
        $stmt = $this->pdo->query($sql);
        return $stmt === false ? false : $stmt;
    }

    public function error(): string
    {
        $info = $this->pdo ? $this->pdo->errorInfo() : [];
        return (string)($info[2] ?? '');
    }

    public function errno(): int
    {
        $info = $this->pdo ? $this->pdo->errorInfo() : [];
        return (int)($info[1] ?? 0);
    }

    public function numRows($result): int
    {
        return $result->rowCount();
    }

    public function selectDb($database)
    {
        return $this->pdo->exec('USE `' . str_replace('`', '', $database) . '`') !== false;
    }

    public function fetchAssoc($result)
    {
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchRow($result)
    {
        return $result->fetch(\PDO::FETCH_NUM);
    }

    public function fetchArray($result, $type)
    {
        return $result->fetch(\PDO::FETCH_BOTH);
    }

    public function affectedRows(): int
    {
        return 0;
    }

    public function escapeString(string $string): string
    {
        return substr($this->pdo->quote($string, \PDO::PARAM_STR), 1, -1);
    }

    public function lastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }

    public function freeResult($result)
    {
        return $result->closeCursor();
    }
}
