<?php
namespace DenDev\Plpwpredirect\Test;
use DenDev\Plpwpredirect\Lib\DBRuleManager;


class DBRuleManagerTest extends \WP_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function test_instanciate()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertInstanceOf( 'DenDev\Plpwpredirect\Lib\DBRuleManager', $object );
	}

	public function test_add_redirection()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->add_redirection( 'uiouoi.com', 'jkljkl.be' ) );
	}

	public function test_delete_redirection()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->add_redirection( 'uiouoi.com', 'jkljkl.be' ) );
		$this->assertTrue( $object->delete_redirection( 1 ) );
	}

	public function test_delete_redirections()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->add_redirection( 'un.com', 'vers_un.be' ) );
		$this->assertTrue( $object->add_redirection( 'deux.com', 'vers_deux.be' ) );
		$this->assertTrue( $object->add_redirection( 'trois.com', 'vers_trois.be' ) );
		$this->assertTrue( $object->delete_redirections( array( 1, 2, 3 ) ) );
	}

	public function test_redirection_to()
	{
		$object = new DBRuleManager( 'test_table_redirect' );
		$this->assertTrue( $object->add_redirection( 'origin.com', 'redirect.be' ) );
		$this->assertArrayHasKey( 'code', $object->get_redirection( 'origin.com' ) );
		$this->assertContains( 'redirect.be', $object->get_redirection( 'origin.com' ) );

		// update
		$this->assertTrue( $object->add_redirection( 'origin.com', 'redirect_surchage.be' ) );
		$this->assertContains( 'redirect_surchage.be', $object->get_redirection( 'origin.com' ) );
	}

	public function tearDown()
	{
		parent::tearDown();
	}

}
