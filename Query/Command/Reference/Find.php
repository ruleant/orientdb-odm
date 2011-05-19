<?php

/**
 * Find class
 *
 * @package    Orient
 * @subpackage Query
 * @author     Alessandro Nadalin <alessandro.nadalin@gmail.com>
 */

namespace Orient\Query\Command\Reference;

use Orient\Query\Command;

class Find extends Command
{
  const SCHEMA = "FIND REFERENCES :Rid :ClassList";
  
  public function __construct($rid)
  {
    parent::__construct();
    
    $this->setRid($rid);
  }

  public function setRid($rid)
  {
    $this->setToken('Rid', array($rid), false);
  }

  public function in(array $classes, $append = true)
  {
    $this->setToken('ClassList', $classes, $append);
  }
}
