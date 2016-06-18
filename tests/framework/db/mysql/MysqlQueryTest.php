<?php
/**
 * Created by PhpStorm.
 * User: arugalev
 * Date: 6/18/16
 * Time: 6:52 PM
 */

namespace yiiunit\framework\db\mysql;


use yii\db\Query;
use yiiunit\framework\db\QueryTest;

class MysqlQueryTest extends QueryTest
{

    public $driverName = 'mysql';

    public function testSelectLock()
    {
        $db = $this->getConnection();
        $db->beginTransaction();
        $query = (new Query())
            ->select('*')
            ->from('order')
            ->selectLock(Query::SELECT_LOCK_SHARED);
        $query->all($db);
        sleep(60);
    }
}