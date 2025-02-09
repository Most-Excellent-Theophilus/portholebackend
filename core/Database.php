<?php


abstract class Database
{
    protected $pdo;

    public function __construct()
    {
        $pdo = new PDO('sqlite:' . __DIR__ . '/sqlite/database.db');
        $this->pdo = $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function getTableName(): string;

    // 
// 
// 
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->getTableName()} ($columns, created_at, updated_at) VALUES ($values, :created_at, :updated_at)";

        $currentDateTime = date("Y-m-d H:i:s");
        $dates = ['created_at' => $currentDateTime, 'updated_at' => $currentDateTime];
        $sqlData = $data + $dates;

        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($sqlData);
            if ($result) {
                return ['status' => 'success', 'message' => 'Row created'];
            } else {
                return ['status' => 'fail', 'message' => 'Unable to create row'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
// 
// 
// 
    public function read($id)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return ['status' => 'success', 'data' => $result];
            } else {
                return ['status' => 'fail', 'message' => 'no such row'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
// 
// 
// 
    public function update($id, array $data)
    {
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ', ');
        $currentDateTime = date("Y-m-d H:i:s");
        $data = $data + array("updated_at" => $currentDateTime);

        $sql = "UPDATE {$this->getTableName()} SET $setClause, updated_at = :updated_at WHERE id = :id";
        // echo $sql;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $dat = $stmt->execute($data);
            if ($dat) {
                return ['status' => 'success', 'message' => 'Row updated'];
            } else {
                return ['status' => 'fail', 'message' => 'Row Not updated'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
// 
// 
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->getTableName()} WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $result = $stmt->execute();
            if ($result) {

                return ['status' => 'success', 'message' => 'row deleted'];
            } else {
                return ['status' => 'fail', 'message' => 'unable to delete row'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
    // 
    // 


    public function search($conditions)
    {
        // Example: $conditions = ['name' => '%sam%', 'email' => '%gmail.com'];
        $whereClause = implode(' AND ', array_map(fn($key) => "$key LIKE :$key", array_keys($conditions)));

        $sql = "SELECT * FROM {$this->getTableName()} WHERE $whereClause";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($conditions);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                return ['status' => 'success', 'data' => $results];
            } else {
                return ['status' => 'fail', 'message' => 'No matching records found'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }

    public function find($conditions)
    {
        // Example: $conditions = ['id' => 1];
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));

        $sql = "SELECT * FROM {$this->getTableName()} WHERE $whereClause";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($conditions);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return ['status' => 'success', 'data' => $result];
            } else {
                return ['status' => 'fail', 'message' => 'No matching record found'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
    // 
    // 
    // 
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->getTableName()} ORDER BY id DESC";

        try {
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return ['status' => 'success', 'data' => $result];
            } else {
                return ['status' => 'fail', 'message' => 'Unable to get data'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
    // 
    // 
    // 
    public function getColumnValues($columnName)
    {
        $sql = "SELECT $columnName FROM {$this->getTableName()}";

        try {
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($result) {
                return ['status' => 'success', 'data' => $result];
            } else {
                return ['status' => 'fail', 'message' => 'Unable to get column values'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
    // 
    // 
    // 

    public function countAll()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->getTableName()}";

        try {
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return ['status' => 'success', 'count' => $result['count']];
            } else {
                return ['status' => 'fail', 'message' => 'Unable to count rows'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }

    // 
    // 
    // 
    public function innerJoin($table, $onConditions)
    {
        $joinConditions = $this->buildJoinConditions($onConditions);
        if (!$joinConditions) {
            return ['status' => 'fail', 'message' => 'Invalid join conditions'];
        }

        $sql = "SELECT * FROM {$this->getTableName()} INNER JOIN $table ON $joinConditions";

        return $this->executeQuery($sql);
    }
    // 
// 
// 
    public function leftJoin($table, $onConditions)
    {
        $joinConditions = $this->buildJoinConditions($onConditions);
        if (!$joinConditions) {
            return ['status' => 'fail', 'message' => 'Invalid join conditions'];
        }

        $sql = "SELECT * FROM {$this->getTableName()} LEFT JOIN $table ON $joinConditions";

        return $this->executeQuery($sql);
    }

    // 
    // 
    // 
    public function rightJoin($table, $onConditions)
    {
        $joinConditions = $this->buildJoinConditions($onConditions);
        if (!$joinConditions) {
            return ['status' => 'fail', 'message' => 'Invalid join conditions'];
        }

        $sql = "SELECT * FROM {$this->getTableName()} RIGHT JOIN $table ON $joinConditions";

        return $this->executeQuery($sql);
    }

    // 
    // 
    // 
    public function fullOuterJoin($table, $onConditions)
    {
        $joinConditions = $this->buildJoinConditions($onConditions);
        if (!$joinConditions) {
            return ['status' => 'fail', 'message' => 'Invalid join conditions'];
        }

        $sql = "SELECT * FROM {$this->getTableName()} FULL OUTER JOIN $table ON $joinConditions";

        return $this->executeQuery($sql);
    }
    // 
    // 
    // 

    private function buildJoinConditions($conditions)
    {
        if (!is_array($conditions) || empty($conditions)) {
            return '';
        }

        return implode(' AND ', array_map(function ($key, $value) {
            return "$key = $value";
        }, array_keys($conditions), $conditions));
    }
    // 
// 
// 
    private function executeQuery($sql)
    {
        try {
            $stmt = $this->pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return ['status' => 'success', 'data' => $result];
            } else {
                return ['status' => 'fail', 'message' => 'No matching records found'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Query failed: ' . $e->getMessage()];
        }
    }
}

// $db = new Database();
// $result = $db->innerJoin('other_table', ['table.column' => 'other_table.column']);
// if ($result['status'] === 'success') {
//     $data = $result['data'];
//     // Process $data as needed
// } else {
//     echo "Error: " . $result['message'];
// }
