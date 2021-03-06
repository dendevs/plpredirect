<?php
namespace DenDev\Plpwpredirect\Lib;
use DenDev\Plpwpredirect\Lib\AdminNotice;


class AdminHandler // FIXME ne signifie rien PostHandler ou AdminPostHandler ici l'ambiguité existe avec ajaxhandler, interet de reunir ajax et post handler en une seul element ?
{
    private $_redirect;
    private $_admin_notice;


    public function __construct( $redirect, $args = array() )
    {
	$this->_redirect = $redirect;

	add_action( 'admin_post_add_rule', array( $this, 'submit_add_rule_callback' ), 99 );
	add_action( 'admin_post_delete_rule', array( $this, 'submit_delete_rule_callback' ), 99 );
    }

    public function submit_add_rule_callback()
    {
	$back_url = ( array_key_exists( 'back_url', $_REQUEST ) ) ? $_REQUEST['back_url'] : false;
	$origin = ( array_key_exists( 'origin', $_REQUEST ) ) ? $_REQUEST['origin'] : false;
	$bound_to = ( array_key_exists( 'bound_to', $_REQUEST ) ) ? $_REQUEST['bound_to'] : false;
	$code = ( array_key_exists( 'code', $_REQUEST ) ) ? $_REQUEST['code'] : 301;
	$code = ( $code == 301 || $code == 302 ) ? $code : 301;

	$ok = $this->_redirect->add_redirection( $origin, $bound_to, $code );
	if( $ok )
	{
	    $status_header = '200';
	}
	else
	{
	    $status_header = '400';
	}

	wp_safe_redirect( add_query_arg( 'status', $status_header, $back_url ) );
	exit;
    }

    public function submit_delete_rule_callback()
    {
	$back_url = ( array_key_exists( 'back_url', $_REQUEST ) ) ? $_REQUEST['back_url'] : false;
	$ids_rule = ( array_key_exists( 'rules_to_delete', $_REQUEST ) ) ? $_REQUEST['rules_to_delete'] : false;

	$ok = $this->_redirect->delete_redirections( $ids_rule );
	if( $ok )
	{
	    $status_header = '200';
	}
	else
	{
	    $status_header = '400';
	}


	wp_safe_redirect( add_query_arg( 'status', $status_header, $back_url ) );
	exit;
    }
}
