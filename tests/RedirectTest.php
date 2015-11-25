<?php
namespace DenDev\Plpwpredirect\Test;
use DenDev\Plpwpredirect\Redirect;


class RedirectTest extends \PHPUnit_Framework_TestCase 
{
	public function test_instanciate()
	{
		$object = new Redirect();
		$this->assertInstanceOf( 'DenDev\Plpwpredirect\Redirect', $object );
	}

	/*
	public function test_set_update_manager()
	{
		$object = new Redirect();
		$this->assertTrue( $object->set_update_manager() );
	}
	 */
}
