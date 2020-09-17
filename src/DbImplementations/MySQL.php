<?php declare(strict_types=1);

namespace DbMockLibrary\DbImplementations;

use DbMockLibrary\AbstractImplementation;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\DbOperationFailedException;
use PDO;
use SimpleArrayLibrary\SimpleArrayLibrary;
use UnexpectedValueException;

class MySQL extends AbstractImplementation
{
    /**
     * @var ?object $instance
     */
    protected static ?object $instance = null;

    /**
     * @var PDO $connection
     */
    protected PDO $connection;

    /**
     * @var array $primaryKeys
     */
    protected array $primaryKeys;

    /**
     * @param array $initialData
     * @param string $serverName
     * @param string $database
     * @param string $username
     * @param string $password
     * @param array $dependencies
     *
     * @return void
     * @throws AlreadyInitializedException
     */
    public static function initMySQL(
        array $initialData,
        string $serverName,
        string $database,
        string $username,
        string $password,
        array $dependencies
    ): void {
        if (!static::$instance) {
            if (empty($serverName)) {
                throw new UnexpectedValueException('Invalid server name');
            }
            if (empty($database)) {
                throw new UnexpectedValueException('Invalid database name');
            }
            if (empty($username)) {
                throw new UnexpectedValueException('Invalid username');
            }
            if (!SimpleArrayLibrary::isAssociative($initialData) && !empty($initialData)) {
                throw new UnexpectedValueException('Invalid table names');
            }

            static::$initialData = $initialData;
            static::initDependencyHandler($initialData, $dependencies);
            static::$instance->connection = new PDO('mysql:host=' . $serverName . ';dbname=' . $database, $username,
                $password);
            static::$instance->primaryKeys = [];
            foreach ($initialData as $table => $data) {
                $stmt = static::$instance->connection->prepare("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
                $stmt->execute();
                static::$instance->primaryKeys[$table] = [];
                foreach ($stmt->fetchAll() as $row) {
                    static::$instance->primaryKeys[$table][] = $row['Column_name'];
                }
            }

            foreach (static::$instance->primaryKeys as $table => $keys) {
                foreach ($initialData[$table] as $row) {
                    if (!SimpleArrayLibrary::hasAllKeys($row, $keys)) {
                        throw new UnexpectedValueException('Missing keys in initial data for table: ' . $table);
                    }
                }
            }
        } else {
            throw new AlreadyInitializedException('MySQL library already initialized');
        }
    }

    /**
     * Insert into database
     *
     * @param string $collection
     * @param string $id
     *
     * @return void
     * @throws DbOperationFailedException
     */
    protected function insert(string $collection, string $id): void
    {
        $data = $this->data[$collection][$id];
        $columns = array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data));
        $stmt = $this->connection->prepare(
            'INSERT INTO ' . $collection . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(', ',
                $columns) . ');'
        );
        if (!$stmt->execute($data)) {
            throw new DbOperationFailedException('Insert failed');
        }
        $this->recordInsert($collection, $id);
    }

    /**
     * Delete from database
     *
     * @param string $collection
     * @param string $id
     *
     * @return void
     * @throws DbOperationFailedException
     */
    protected function delete(string $collection, string $id): void
    {
        $query = 'DELETE FROM ' . $collection . ' WHERE ';
        $conditions = [];
        $values = [];
        foreach ($this->primaryKeys[$collection] as $key) {
            $conditions[] = '`' . $key . '` = ' . ':' . $key;
            $values[$key] = $this->data[$collection][$id][$key];
        }
        $query .= implode(' AND ', $conditions);
        $stmt = $this->connection->prepare($query);

        if (!$stmt->execute($values)) {
            throw new DbOperationFailedException('Delete failed');
        }
    }
}
