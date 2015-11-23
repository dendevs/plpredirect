<?php
namespace DenDev\Plpwpredirect\Test;
use DenDev\Plpwpredirect\Lib\DBRuleManager;


class DBRuleManagerTest extends \WP_UnitTestCase
{
	public function test_instanciate()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertInstanceOf( 'DenDev\Plpwpredirect\Lib\DBRuleManager', $object );
	}

	public function test_create_table()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->create_table() );
	}

	public function test_add_redirection()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->create_table() );
		$this->assertTrue( $object->add_redirection( 'uiouoi.com', 'jkljkl.be' ) );
	}

	public function test_redirection_to()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->create_table() );
		$this->assertTrue( $object->add_redirection( 'uiouoi.com', 'jkljkl.be' ) );
		$this->assertArrayHasKey( '301', $object->get_redirection( 'uiouoi.com' ) );
		$this->assertContains( 'jkljkl.be', $object->get_redirection( 'uiouoi.com' ) );
	}

}
