<?php
namespace DenDev\Plpwpredirect\Lib;


class AdminNotice
{
    private $_args;
    private $_messages;


    public function __construct( $redirect, $args = array() )
    {
	$this->_redirect = $redirect; // au niveau du concept on est avec kernel ( redirect n'est qu'un substitut )
	$this->_args = $args;
	add_action( 'admin_notices', array( $this, 'admin_notice_error_callback' ), 99 ); 
	add_action( 'admin_notices', array( $this, 'admin_notice_succes_callback' ), 99 ); 
    }

  /**
     * Gere les argument de configuration interne
     *
     * pour les valeures propre a ce composant ci
     *
     * @param string $arg_name nom de l'argument
     *
     * @return mixed la valeur ou false
     */
    public function get_args_value( $arg_name )
    {
	$value = false;

	if( array_key_exists( $arg_name, $this->_args ) )
	{
	    $value = $this->_args[$arg_name];
	}

	return $value;
    }

    public function admin_notice_error_callback() 
    {
	$status = ( array_key_exists( 'status', $_GET ) ) ? $_GET['status'] : false;
	if( $status && $status >= 400 )
	{
	    $template_name = 'admin_notice_error.php';
	    $template_path = $this->_redirect->get_config_value( 'template_path' ) . $template_name; // TODO distinction entre args et config values
	    $messages = "Erreur";

	    include( $template_path );
	}
    }

    public function admin_notice_succes_callback() 
    {
	$status = ( array_key_exists( 'status', $_GET ) ) ? $_GET['status'] : false;
	if( $status && $status < 400 )
	{
	    $template_name = 'admin_notice_success.php';
	    $template_path = $this->_redirect->get_config_value( 'template_path' ) . $template_name; // TODO distinction entre args et config values
	    $messages = "Ok";

	    include( $template_path );
	}
    }

}
