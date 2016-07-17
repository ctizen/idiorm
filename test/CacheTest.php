<?php

namespace Idiorm;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    const ALTERNATE = 'alternate'; // Used as name of alternate connection

    public function setUp()
    {
        // Set up the dummy database connections
        ORM::setDb(new \MockPDO('sqlite::memory:'));
        ORM::setDb(new \MockDifferentPDO('sqlite::memory:'), self::ALTERNATE);

        // Enable logging
        ORM::configure('logging', true);
        ORM::configure('logging', true, self::ALTERNATE);
        ORM::configure('caching', true);
        ORM::configure('caching', true, self::ALTERNATE);
    }

    public function tearDown()
    {
        ORM::resetConfig();
        ORM::resetDb();
    }

    // Test caching. This is a bit of a hack.
    public function testQueryGenerationOnlyOccursOnce()
    {
        ORM::forTable('widget')->where('name', 'Fred')->where('age', 17)->findOne();
        ORM::forTable('widget')->where('name', 'Bob')->where('age', 42)->findOne();
        $expected = ORM::getLastQuery();
        ORM::forTable('widget')->where('name', 'Fred')->where('age', 17)->findOne(); // this shouldn't run a query!
        $this->assertEquals($expected, ORM::getLastQuery());
    }

    public function testQueryGenerationOnlyOccursOnceWithMultipleConnections()
    {
        // Test caching with multiple connections (also a bit of a hack)
        ORM::forTable('widget', self::ALTERNATE)->where('name', 'Steve')->where('age', 80)->findOne();
        ORM::forTable('widget', self::ALTERNATE)->where('name', 'Tom')->where('age', 120)->findOne();
        $expected = ORM::getLastQuery();
        ORM::forTable('widget', self::ALTERNATE)->where('name', 'Steve')->where('age', 80)->findOne(); // this shouldn't run a query!
        $this->assertEquals($expected, ORM::getLastQuery(self::ALTERNATE));
    }
}
