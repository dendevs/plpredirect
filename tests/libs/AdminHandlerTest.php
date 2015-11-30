<?php
namespace DenDev\Plpwpredirect\Test;
use DenDev\Plpwpredirect\Lib\AdminHandler;
use DenDev\Plpwpredirect\Redirect;


class AdminHandlerTest extends \WP_UnitTestCase
{
	private $_redirect;


	public function setUp()
	{
		$root_path = plugin_dir_path( __FILE__ );
		$root_url = plugins_url() . '/package/';

		$this->_redirect = Redirect::get_instance( array( 
			'root_path' => $root_path,
			'root_url' => $root_url,
		) );
	}

	public function wp_safe_redirect($location, $status)
	{
	}

	public function test_instanciate()
	{
		$object = new AdminHandler( $this->_redirect );
	}

	/* 
Cannot modify header information - headers already sent by (output started at /tmp/wordpress-tests-lib/includes/bootstrap.php:54)
	public function test_submit_add_rule_callback()
	{
		$root_path = plugin_dir_path( __FILE__ );
		$root_url = plugins_url() . '/package/';

		$redirect = Redirect::get_instance( array( 
			'root_path' => $root_path,
			'root_url' => $root_url,
		) );

		$object = new AdminHandler( $redirect );

		$_REQUEST['origin'] = 'origin.com';
		$_REQUEST['bound_to'] = 'bound_to.com';
		$_REQUEST['code'] = '301';
		$object->submit_add_rule_callback();
	}
	 */
}
