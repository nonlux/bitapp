<?php
/**
 * Created by PhpStorm.
 * User: nonlux
 * Date: 16.11.14
 * Time: 13:50
 */

namespace Nonlux\BitApp\Console\Helper;
use  Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper  as BaseHelper;

class EntityManagerHelper  extends  BaseHelper{
    public function getName()
    {
        return 'em';
    }

} 