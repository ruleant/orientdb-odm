<?php

/**
 * QueryTest
 *
 * @package    Orient
 * @subpackage Test
 * @author     Alessandro Nadalin <alessandro.nadalin@gmail.com>
 * @version
 */

use Orient\Query\Command\Select;
use Orient\Query\Command\Insert;

class QueryTest extends PHPUnit_Framework_TestCase
{
  public function initialize()
  {
    $this->query = new \Orient\Query();
  }

  public function testGetTokens()
  {
    $query  = new \Orient\Query(array('myClass'));

    $this->assertEquals(Select::getTokens(), $query->getTokens());

    $query  = new \Orient\Query();

    $this->assertEquals(Insert::getTokens(), $query->insert()->getTokens());
  }

  public function testSelect()
  {
    $query  = new \Orient\Query(array('myClass'));
    $query->select(array('name', 'username', 'email'))
          ->from(array('12:0', '12:1'), false)
          ->where('any() traverse ( any() like "%danger%" )')
          ->orWhere("1 = ?", 1)
          ->andWhere("links = ?", 1)
          ->limit(20)
          ->orderBy('username')
          ->orderBy('name', true, true)
          ->range("12:0", "12:1");
    $sql    =
      'SELECT name, username, email FROM [12:0, 12:1] WHERE any() traverse ( any() like "%danger%" ) OR 1 = "1" AND links = "1" ORDER BY name, username LIMIT 20 RANGE 12:0, 12:1'
    ;

    $this->assertEquals($sql, $query->getRaw());
  }

  public function testResetWhere()
  {
    $query  = new \Orient\Query(array('myClass'));
    $query->where('the sky = ?', 'blue');
    $sql    =
      'SELECT FROM myClass WHERE the sky = "blue"'
    ;

    $this->assertEquals($sql, $query->getRaw());

    $query->resetWhere();
    $sql    =
      'SELECT FROM myClass'
    ;

    $this->assertEquals($sql, $query->getRaw());

    $query  = new \Orient\Query(array('myClass'));
    $query->where('the sky = ?', 'blue');
    $sql    =
      'SELECT FROM myClass WHERE the sky = "blue"'
    ;

    $this->assertEquals($sql, $query->getRaw());
  }

  public function testInsert()
  {
    $query  = new \Orient\Query(array('myClass'));
    $query->insert()
          ->into("myClass")
          ->fields(array('name', 'relation', 'links'))
          ->values(array(
            'hello', array('10:1'), array('10:1', '11:1')
          ));
    $sql    =
      'INSERT INTO myClass (name, relation, links) VALUES ("hello", 10:1, [10:1, 11:1])'
    ;

    $this->assertEquals($sql, $query->getRaw());
  }

  public function testGrant()
  {
    $query  = new \Orient\Query();
    $query->grant("read")
          ->to("myUser")
          ->to("myOtherUser")
          ->on("server");
    $sql    =
      'GRANT read ON server TO myOtherUser'
    ;

    $this->assertEquals($sql, $query->getRaw());
  }
}