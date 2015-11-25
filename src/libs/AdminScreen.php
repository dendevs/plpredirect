<?php
namespace DenDev\Plpwpredirect\Lib;


/**
 * Ajoute un affichie pour la creation de regles de redirection
 *
 */
class AdminScreen
{
    public function __construct( $args )
    {
	$this->_args = $args;
	add_action( 'admin_menu', array( $this, 'redirect_admin_menu_callback' ) );
    }

    public function redirect_admin_menu_callback()
    {
	add_menu_page( 'Redirect', 'Redirect', 'manage_options', 'redirect_page', array( $this, 'render_page_redirect' ), plugins_url( 'package/img/button_html.png' ), 6 ); 

    }

    public function render_page_redirect()
    {
    }

}
