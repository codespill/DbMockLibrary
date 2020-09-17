<?php declare(strict_types=1);

namespace DbMockLibrary;

use SimpleArrayLibrary\SimpleArrayLibrary;
use UnexpectedValueException;

abstract class AbstractImplementation extends DependencyHandler
{
    /**
     * @var array $insertedIntoDb
     */
    protected array $insertedIntoDb = [];

    /**
     * Fill some or all collections with dummy data
     *
     * @param array $records
     *
     * @return void
     */
    public function setUp(array $records = []): void
    {
        if (empty($records)) {
            foreach ($this->data as $collection => $rows) {
                $records[$collection] = array_keys($rows);
            }
        } elseif (SimpleArrayLibrary::countMaxDepth($records) == 1) {
            $this->validateCollections($records);

            $temp = [];
            foreach ($records as $collection) {
                $temp[$collection] = array_keys($this->data[$collection]);
            }
            $records = $temp;
        } else {
            foreach ($records as $collection => $ids) {
                $this->validateIds($collection, $ids);
            }
        }

        if (empty($this->dependencies)) {
            foreach ($records as $collection => $ids) {
                foreach ($ids as $id) {
                    $this->insert($collection, $id);
                }
            }
        } else {
            foreach ($this->prepareDependencies($records) as $recordToInsert) {
                foreach ($recordToInsert as $collection => $ids) {
                    foreach ($ids as $id) {
                        $this->insert($collection, $id);
                    }
                }
            }
        }
    }

    /**
     * TearDown remove dummy data from one or more collections
     *
     * @param array $records
     *
     * @return void
     * @throws UnexpectedValueException
     */
    public function tearDown(array $records = []): void
    {
        if (empty($records)) {
            foreach ($this->data as $collection => $rows) {
                $records[$collection] = array_keys($rows);
            }
        } elseif (SimpleArrayLibrary::countMaxDepth($records) == 1) {
            $this->validateCollections($records);

            $temp = [];
            foreach ($records as $collection) {
                $temp[$collection] = array_keys($this->data[$collection]);
            }
            $records = $temp;
        } else {
            foreach ($records as $collection => $ids) {
                $this->validateIds($collection, $ids);
            }
        }

        foreach ($records as $collection => $ids) {
            foreach ($ids as $id) {
                $this->delete($collection, $id);
            }
        }
    }

    /**
     * Insert into database
     *
     * @param string $collection
     * @param $id
     *
     * @return void
     */
    abstract protected function insert(string $collection, $id): void;

    /**
     * Delete from database
     *
     * @param string $collection
     * @param $id
     *
     * @return void
     */
    abstract protected function delete(string $collection, $id): void;

    /**
     * @param string $collection
     * @param $id
     *
     * @return void
     */
    protected function recordInsert(string $collection, $id): void
    {
        if (!in_array([$collection => $id], $this->insertedIntoDb)) {
            $this->insertedIntoDb[] = [$collection => $id];
        }
    }

    /**
     * @return void
     */
    public function cleanUp(): void
    {
        $reverseOrder = array_reverse($this->insertedIntoDb);
        for ($i = 0; $i < count($reverseOrder); $i++) {
            foreach ($reverseOrder[$i] as $collection => $id) {
                $this->delete($collection, $id);
            }
        }
        $this->insertedIntoDb = [];
    }
} 
