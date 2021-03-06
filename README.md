DbMockLibrary
==================
[![Build Status](https://travis-ci.org/ajant/DbMockLibrary.svg?branch=master)](https://travis-ci.org/ajant/DbMockLibrary)
[![Coverage Status](https://coveralls.io/repos/ajant/DbMockLibrary/badge.svg?branch=master&service=github)](https://coveralls.io/github/ajant/DbMockLibrary?branch=master)

Db mocking & dummy data management library


[Wiki](https://github.com/ajant/DbMockLibrary/wiki)

This is a database stubbing/mocking/prototyping library. Its principal uses are meant to be:

1. testing the application without using actual database (by mocking data persistence layer, using DbMockLibrary)

2. quick prototyping, while delaying the writing of any database specific code (again by mocking data persistence layer, using DbMockLibrary)

3. dummy data management during development phase

Description:

1. If data persistence code is kept separate from business logic code, in a different layer of the application, then data persistence layer can
be mocked using DbMockLibrary during testing. That way objects that call on data persistence layer can be tested, without actually using a
real database. As a result tests are faster and better code & test separation is achieved. DbMockLibrary could be used to mock data persistence
layer functionality in the testing environment

2. When project is in prototyping stage, often making choice on database is not necessarily needed at that time. Sometimes it's even beneficial
to postpone the decision for a while during that phase, until some features/architectural solutions take shape. What is needed is to have some
"dummy data" available, to test out features and concepts with it. DbMockLibrary provides feature rich "dummy data" platform.

3. During development, it's often convenient to have some easy way to load/remove "dummy data" from the database, in order to be able to
test out features, without having to create dumps from the production database. DbMockLibrary provides a simple way to manage this process for
some of the most popular databases

Requirements
============

You'll need: PHP version 5.4+

Installation
============
Install the latest version with composer:<br/>
```
require "ajant/db-mock-library": ~1
```

Auto-load the library:
```php
use DbMockLibrary/DbMockLibrary
```

As of now MySQL, MongoDb and Elasticsearch databases have been implemented.

Quick start
===========
Here's the example, how to use the library for testing DB features of the application.

**MySQL**
-
Bootstrapping:
```php
...
// 2 tables, 2 rows each
$data = [
    'table_1' => [
        -1 => ['foo' => 20, 'id' => -1],
        -2 => ['foo' => 50, 'id' => -2]
    ],
    'table_2' => [
        -1 => ['bar' => 30, 'id' => -1, 'table_1_id' => -1],
        -2 => ['bar' => 10, 'id' => -2, 'table_1_id' => -2]
    ]
];
// table_1_id is foreign key, referencing id column
$dependencies = [
    [
        DependencyHandler::DEPENDENT => ['table_2' => 'table_1_id'],
        DependencyHandler::ON        => ['table_1' => 'id']
    ]
];
// initialize MySQL
MySQL::initMySQL($data, 'localhost', 'DbMockLibraryTest', 'root', '', $dependencies);
...
```
Test set up:
```php
...
// inserts both rows of table_2 and both rows of table_1, because
MySQL::getInstance()->setUp(['table_2' => [-1, -2]]);
...
```
Test tear down:
```php
...
// removes all rows inserted during set up phase
MySQL::getInstance()->cleanUp();
...
```
**Elasticsearch**
-
Note:

It is presumed that all indexes and mappings for records that are to be used in testing are already in Elasticsearch database.

Bootstrapping:

```php
...
// 4 indexes, 2 rows each
// 4th index is percolator index
$data = [
    'index_1' => [
        0 => ['foo' => 20, 'id' => -1],
        1 => ['foo' => 50, 'id' => -2],
    ],
    'index_2' => [
        0 => ['bar' => 30, 'id' => -1, 'index_1_id' => -1],
        1 => ['bar' => 10, 'id' => -2, 'index_1_id' => -2],
    ],
    'index_3' => [
        'record1' => ['field1' => 'value11', 'field2' => 'value12'],
        'record2' => ['field1' => 'value21', 'field2' => 'value22'],
    ],
    'index_4' => [
        'percolatorRecord1' => [
            'routing' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'minimum_number_should_match' => 1,
                        'should' => [
                            [
                                'match' => [
                                    'someField' => [
                                        'query' => 'foo',
                                        'operator' => 'and'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'percolatorRecord2' => [
            'routing' => 1,
            'body' => [
                'query' => [
                    'terms' => [
                        'someField' => [
                            'someValue'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
// index_1_id is foreign key, referencing id column
$dependencies = [
    [
        DependencyHandler::DEPENDENT => ['index_2' => 'index_1_id'],
        DependencyHandler::ON        => ['index_1' => 'id'],
    ],
];
$indexTypes = [
    'index_1' => 'someType',
    'index_2' => 'someType',
    'index_3' => 'someType',
    'index_4' => '.percolator',
];
// initialize Elasticsearch
$client = \Elasticsearch\ClientBuilder::create()->setHosts(['http://localhost:9200'])->build();
Elasticsearch::initElasticsearch($client, $data, $dependencies, $indexTypes);
...
```
Test set up features:
```php
...
// inserts both rows of index_2 and both rows of index_1, because
Elasticsearch::getInstance()->setUp(['index_2' => [0, 1]]);
// inserts all indexes
Elasticsearch::getInstance()->setUp();
// inserts only index_1
Elasticsearch::getInstance()->setUp(['index_1']);
...
```
Test tear down features:
```php
...
// removes all rows inserted during set up phase, including dependencies
Elasticsearch::getInstance()->cleanUp();
// removes all indexes
Elasticsearch::getInstance()->tearDown();
// removes only index_1
Elasticsearch::getInstance()->tearDown(['index_1']);
...
```