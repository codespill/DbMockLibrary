<?php declare(strict_types=1);

namespace DbMockLibrary\DbImplementations;

use DbMockLibrary\AbstractImplementation;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\DbOperationFailedException;
use Exception;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Exception\InvalidArgumentException;
use SimpleArrayLibrary\SimpleArrayLibrary;
use UnexpectedValueException;

class Mongo extends AbstractImplementation
{
    /**
     * @var ?object $instance
     */
    protected static ?object $instance = null;

    /**
     * @var array $initialData
     */
    protected static array $initialData;

    /**
     * @var Database $database
     */
    protected Database $database;

    /**
     * @param array $initialData
     * @param string $database
     * @param array $dependencies
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidArgumentException
     */
    public static function initMongo(array $initialData, string $database, array $dependencies): void
    {
        if (!static::$instance) {
            if (empty($database) || !is_string($database)) {
                throw new UnexpectedValueException('Invalid database name');
            }
            if (!SimpleArrayLibrary::isAssociative($initialData) && !empty($initialData)) {
                throw new UnexpectedValueException('Invalid collection names');
            }

            static::$initialData = $initialData;
            static::initDependencyHandler($initialData, $dependencies);
            $client = new Client();
            static::$instance->database = $client->selectDatabase($database);
        } else {
            throw new AlreadyInitializedException('Mongo library already initialized');
        }
    }

    /**
     * @return ?object
     */
    public static function getInstance(): ?object
    {
        return static::$instance;
    }

    /**
     * Insert into database
     *
     * @param string $collectionName
     * @param string $id
     *
     * @return void
     * @throws DbOperationFailedException
     */
    protected function insert(string $collectionName, string $id): void
    {
        $collection = static::$instance->database->selectCollection($collectionName);
        try {
            $status = $collection->insertOne($this->data[$collectionName][$id], ['w' => 1]);
        } catch (Exception $e) {
            throw new DbOperationFailedException('Insert failed');
        }
        if ($status->getInsertedCount() !== 1) {
            throw new DbOperationFailedException('Insert failed');
        }
        $this->recordInsert($collectionName, $id);
    }

    /**
     * Delete from database
     *
     * @param string $collectionName
     * @param string $id
     *
     * @return void
     * @throws DbOperationFailedException
     */
    protected function delete(string $collectionName, string $id): void
    {
        $collection = static::$instance->database->selectCollection($collectionName);
        try {
            $status = $collection->deleteOne(['_id' => $this->data[$collectionName][$id]['_id']], ['w' => 1]);
        } catch (Exception $e) {
            throw new DbOperationFailedException('Delete failed');
        }
        if ($status->getDeletedCount() !== 1) {
            throw new DbOperationFailedException('Delete failed');
        }
    }
}
