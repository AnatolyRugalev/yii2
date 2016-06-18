<?php

namespace yiiunit\framework\db\mysql;

use yii\db\Expression;
use yii\db\Query;
use yii\db\Schema;
use yiiunit\framework\db\QueryBuilderTest;

/**
 * @group db
 * @group mysql
 */
class MysqlQueryBuilderTest extends QueryBuilderTest
{
    /**
     * this is not used as a dataprovider for testGetColumnType to speed up the test
     * when used as dataprovider every single line will cause a reconnect with the database which is not needed here
     */
    public function columnTypes()
    {
        return array_merge(parent::columnTypes(), [
        	[
        	    Schema::TYPE_PK . ' AFTER `col_before`',
        	    $this->primaryKey()->after('col_before'),
        	    'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY AFTER `col_before`'
        	],
        	[
        	    Schema::TYPE_PK . ' FIRST',
        	    $this->primaryKey()->first(),
        	    'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST'
        	],
        	[
        	    Schema::TYPE_PK . ' FIRST',
        	    $this->primaryKey()->first()->after('col_before'),
        	    'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST'
        	],
        	[
        	    Schema::TYPE_PK . '(8) AFTER `col_before`',
        	    $this->primaryKey(8)->after('col_before'),
        	    'int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY AFTER `col_before`'
        	],
        	[
        	    Schema::TYPE_PK . '(8) FIRST',
        	    $this->primaryKey(8)->first(),
        	    'int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST'
        	],
        	[
        	    Schema::TYPE_PK . '(8) FIRST',
        	    $this->primaryKey(8)->first()->after('col_before'),
        	    'int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST'
        	],
        	[
        	    Schema::TYPE_PK . " COMMENT 'test' AFTER `col_before`",
        	    $this->primaryKey()->comment('test')->after('col_before'),
        	    "int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'test' AFTER `col_before`"
        	],
        ]);
    }

	public function testBuildSelectLock()
	{
		// exclusive lock
		$query = (new Query())
			->select('*')
			->from('operations')
			->selectLock(Query::SELECT_LOCK_EXCLUSIVE);
		list ($sql) = $this->getQueryBuilder()->build($query);
		$expected = $this->replaceQuotes('SELECT * FROM [[operations]] FOR UPDATE');
		$this->assertEquals($expected, $sql);

		// shared lock
		$query = (new Query())
			->select('*')
			->from('operations')
			->selectLock(Query::SELECT_LOCK_SHARED);
		list ($sql) = $this->getQueryBuilder()->build($query);
		$expected = $this->replaceQuotes('SELECT * FROM [[operations]] LOCK IN SHARE MODE');
		$this->assertEquals($expected, $sql);
	}
}
