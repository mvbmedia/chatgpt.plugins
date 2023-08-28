<?php
class Database
{
    private $host = DB_HOST;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $name = DB_NAME;

    private $connection;
    private $error;
    private $stmt;

    public function __construct()
    {
        # set connection
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name . ';charset=utf8';

        # set options
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false
        );

        # connect to the database
        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $message) {
            $this->error = $message->getMessage();
        }
    }

    # prepare query
    public function query($query)
    {
        $this->stmt = $this->connection->prepare($query);
    }
    # prepare query
    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }
    # bind value
    public function bind($param, $value, $type = null)
    {
        if (is_array($value)) {
            error_log("Array to string conversion issue. Param: $param, Value: " . print_r($value, true));
            $value = implode(',', $value);
        }
        
        
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindValue($param, $value, $type);
    }
    public function bindParams($stmt, $params)
    {
        foreach ($params as $param => $value) {
            // Voeg hier de juiste datatype logica toe op basis van je gebruik
            $type = PDO::PARAM_STR;
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            }

            $stmt->bindValue($param, $value, $type);
        }
    }
    # execute query
    public function execute(): bool
    {
        error_log("Executing Query: " . $this->stmt->queryString);
        return $this->stmt->execute() ?? false;
    }

    # return resultset
    public function resultset(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }

    # return fetched data
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }

    # return row count
    public function rowCount(): int
    {
        return $this->stmt->rowCount() ?? 0;
    }

    # return column
    public function fetchColumn(): mixed
    {
        return $this->stmt->fetchColumn() ?? null;
    }

    # return last inserted id
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId() ?? '';
    }

    # start multiple query transaction
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction() ?? false;
    }

    # end multiple query transaction
    public function endTransaction(): bool
    {
        return $this->connection->commit() ?? false;
    }

    # cancel transaction
    public function cancelTransaction(): bool
    {
        return $this->connection->rollBack() ?? false;
    }

    # debug parameters
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    # close connection
    public function __destruct()
    {
        $this->connection = null;
    }
}