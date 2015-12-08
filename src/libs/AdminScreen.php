<?php
namespace DenDev\Plpwpredirect\Lib;


/**
 * Ajoute un affichie pour la creation de regles de redirection
 *
 */
class AdminScreen
{
    /**
     * Constructor
     *
     * Se charge de l'action admin_menu
     *
     * @param array tableau de configuration
     */ 
    public function __construct( $args )
    {
	$this->_args = $args;
	add_action( 'admin_menu', array( $this, 'redirect_admin_menu_callback' ), 99 );
	add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts_callback' ), 99 );
    }

    public function add_scripts_callback() // empeche la centralisation...
    {
	$src = $this->get_args_value( 'js_url' ) . 'submit_add_or_delete.js';
	$translation_array = array(
	    'add' => __( 'Ajouter', $this->get_args_value( 'text_domain' ) ),
	    'delete' => __( 'Supprimer', $this->get_args_value( 'text_domain' ) ) 
	);

	wp_register_script( 'submit_add_or_delete', $src, array(), '1.0.0', true );
	wp_localize_script( 'submit_add_or_delete', 'labels', $translation_array );
	wp_enqueue_script( 'submit_add_or_delete', $src, array(), '1.0.0', true );
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

    /**
     * Callback pour wordpress
     *
     * Cree un menu d'apres les arguments recut par l'object
     * 
     * @return void
     */
    public function redirect_admin_menu_callback()
    {
	$parent_menu = $this->get_args_value( 'parent_menu' );
	$page_title = $this->get_args_value( 'page_title' );
	$menu_title = $this->get_args_value( 'menu_title' );
	$capability = $this->get_args_value( 'capability' );
	$menu_slug = $this->get_args_value( 'menu_slug' );
	$template_name = $this->get_args_value( 'template_name' );
	$icon_url = $this->get_args_value( 'img_url' ) . $this->get_args_value( 'icon_name' );
	$position = $this->get_args_value( 'position' );

	if( $parent_menu )
	{
	    add_submenu_page( $parent_menu, $page_title, $menu_title, $capability, $menu_slug, array( $this, 'render_page_redirect' ) ); 
	}
	else
	{
	    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, 'render_page_redirect' ), $icon_url, $position ); 
	}
    }

    /**
     * Methode d'affichage
     *
     * Recupere et inclus le template 
     */
    public function render_page_redirect()
    {
	$template_name = $this->get_args_value( 'template_name' ) . '.php';
	$template_path = $this->get_args_value( 'template_path' ) . $template_name; // TODO distinction entre args et config values
	$datas = $this->_args['datas']; // TODO ajouter des verifs
	$back_url = admin_url( 'admin.php?page=' . $this->get_args_value( 'menu_slug' ) );

	include( $template_path );
    }
}
