<?php
namespace DenDev\Plpwpredirect\Test;
use DenDev\Plpwpredirect\Redirect;


class RedirectTest extends \PHPUnit_Framework_TestCase 
{
	private $_root_path;
	private $_root_url;


	public function setUp()
	{
		$this->_root_path = plugin_dir_path( __FILE__ );
		$this->_root_url = plugins_url() . '/package/';
	}
	public function test_instanciate()
	{
		$object = Redirect::get_instance( array( 
			'root_path' => $this->_root_path,
			'root_url' => $this->_root_url,
		) );
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
