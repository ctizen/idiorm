<?php

namespace Idiorm;

class IdiormResultSetTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        // Enable logging
        ORM::configure('logging', true);

        // Set up the dummy database connection
        $db = new \MockPDO('sqlite::memory:');
        ORM::setDb($db);
    }

    public function tearDown()
    {
        ORM::resetConfig();
        ORM::resetDb();
    }

    public function testGet()
    {
        $IdiormResultSet = new IdiormResultSet();
        $this->assertInternalType('array', $IdiormResultSet->getResults());
    }

    public function testConstructor()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet($result_set);
        $this->assertSame($IdiormResultSet->getResults(), $result_set);
    }

    public function testSetResultsAndGetResults()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet();
        $IdiormResultSet->setResults($result_set);
        $this->assertSame($IdiormResultSet->getResults(), $result_set);
    }

    public function testAsArray()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet();
        $IdiormResultSet->setResults($result_set);
        $this->assertSame($IdiormResultSet->asArray(), $result_set);
    }

    public function testCount()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet($result_set);
        $this->assertSame($IdiormResultSet->count(), 1);
        $this->assertSame(count($IdiormResultSet), 1);
    }

    public function testGetIterator()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet($result_set);
        $this->assertInstanceOf('ArrayIterator', $IdiormResultSet->getIterator());
    }

    public function testForeach()
    {
        $result_set = array('item' => new \stdClass);
        $IdiormResultSet = new IdiormResultSet($result_set);
        $return_array = array();
        foreach ($IdiormResultSet as $key => $record) {
            $return_array[$key] = $record;
        }
        $this->assertSame($result_set, $return_array);
    }

    public function testCallingMethods()
    {
        $result_set = array('item' => ORM:: forTable('test'), 'item2' => ORM:: forTable('test'));
        $IdiormResultSet = new IdiormResultSet($result_set);
        $IdiormResultSet->set('field', 'value')->set('field2', 'value');

        foreach ($IdiormResultSet as $record) {
            $this->assertTrue(isset($record->field));
            $this->assertSame($record->field, 'value');

            $this->assertTrue(isset($record->field2));
            $this->assertSame($record->field2, 'value');
        }
    }
}
