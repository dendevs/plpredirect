<?php
namespace DenDev\Plpwpredirect;
use DenDev\Plpwpredirect\RedirectInterface;
use DenDev\Plpwpredirect\Lib\DBRuleManager;
use DenDev\Plpwpredirect\Lib\AdminScreen;
use DenDev\Plpwpredirect\Lib\AdminHandler;
use DenDev\Plpwpredirect\Lib\AdminNotice;


/**
 *  Redirect 
 */
class Redirect implements RedirectInterface
{
    private static $_instance;

    /** @var object lib for manage rule */
    private $_rule_manager;


    /**
     * Implemente le pattern Singleton
     *
     *
     * @return void
     */
    static function get_instance( $args = array() )
    {
	if (null === static::$_instance) 
	{
	    static::$_instance = new static( false, $args );
	}

	return static::$_instance;
    }

    /**
     * Set le kernel du servie // ici il est le kernel.
     *
     * @param object $krl la ref du kernel auquel appartient le service ou false par defaut.
     *
     * @return void
     */
    private function __construct( $krl = false, $args = array() )
    {
	// parent::__construct( $krl, $args );
	$this->_args = $args;
	$this->_config = $this->get_default_configs(); // TODO adaptability doit prendre le relais

	$this->_set_rule_manager();
	if( $this->get_args_value( 'set_update_manager' ) )
	{
	    $this->_set_update_manager();
	}

	//
	$this->admin_notice = new AdminNotice( $this ); 
    }

    /**
     * Ajoute une regle de redirection.
     * 
     * Sous traite au rule manager.
     * 
     * @return bool true si la rule a etait ajouter.
     */
    public function add_redirection( $origin, $bound_to, $code = '301' )
    {
	return $this->_rule_manager->add_redirection( $origin, $bound_to, $code );
    }

   /**
     * Supprime n regles de redirection.
     * 
     * Sous traite au rule manager.
     *
     * @param array $ids_rule array des id a delete.
     * 
     * @return bool true si la rule a etait ajouter.
     */
    public function delete_redirections( $ids_rule )
    {
	return $this->_rule_manager->delete_redirections( $ids_rule );
    }

    /**
     * Traite le tableau d'argument donnes a l'object.
     *
     * @param string $arg_name nom de l'argument dont on veut la valeur.
     *
     * @return mixed la valeur ou false.
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
     * Configuration par defaut du service.
     *
     * @var string $storage db,file,htacess.
     *
     * @return array tableau associatif option value.
     */
    public function get_default_configs()
    {
	$root_path  = $this->_args['root_path'];
	$root_url = $this->_args['root_url'];
	$parent_menu = ( array_key_exists( 'parent_menu', $this->_args ) ) ? $this->_args['parent_menu'] : false; // FIXME bof la config demander par l'user devrait etre setter ailleurs

	$module_root_path = dirname( plugin_dir_path( __FILE__ ) ) . '/';
	$module_root_url = dirname( dirname( plugins_url( 'Redirect.php', __FILE__ ) ) ) . '/';

	return array( 
	    'root_path' => $root_path,
	    'root_url' => $root_url,
	    'module_root_url' => $module_root_url,
	    'module_root_path' => $module_root_path,
	    'img_url' => $module_root_url . 'assets/img/',
	    'js_url' => $module_root_url . 'assets/js/',
	    'template_path' => $module_root_path. 'templates/',
	    'storage' => 'db',
	    'db_table_name' => 'dwredirect',
	    'file_name' => 'fake_htaccess.php',
	    'file_path' => '',
	    'update_by_user' => true,
	    'update_by_code' => true,
	    'update_by_permalink' => true, // @deprecated 
	    'parent_menu' => $parent_menu,
	    'text_domain' => 'redirect' // FIXME chaque module a son text domain ? // oui car chaqu'un est autonome
	);
    }

    /**
     * Set les informations de base au sujet du service.
     *
     * son nom sous forme slugifier ( mon_serice et non Mon service )
     * son numero de version 
     *
     * @return void
     */
    public function set_service_metas()
    {
	$this->_service_metas = array( 
	    'service_name' => 'wpredirect',
	    'service_version' => '1.0.0',
	);
    }

    /**
     * Simple methode pour recuperer la config
     *
     * @todo Doit etre supprimer pour etre remplacer par adaptability au sein d'un kernel 
     *
     * @param string config_name nom de la config 
     *
     * @return mixed la valeur de config ou false
     */
    public function get_config_value( $config_name )
    {
	$value = false;

	if( array_key_exists( $config_name, $this->_config ) )
	{
	    $value = $this->_config[$config_name];
	}

	return $value;
    }

    // -
    /**
     * 
     * Instancie le manager de rule d'apres la config
     *
     * @return bool true en cas de succes
     */
    private function _set_rule_manager()
    {
	$ok = false;

	$storage = $this->get_config_value( 'storage' );
	if( $storage == 'db' )
	{
	    $this->_rule_manager = new DBRuleManager( $this->get_config_value( 'db_table_name' ) );
	    $ok = true;
	}
	else 
	{
	    throw new \Exception( 'not implemented' );
	}

	return $ok;
    }

    /**
     * Ajout la mise a jour pour le code ou l'user
     *
     * Ajoute une interface admin pour l'ajout de regles de redirection par l'utilisateur.
     * Par default un nouveau menu Redirect est cree en position 6 avec les restrictions manage_options.
     * Si l'option in_menu existe alors l'interface vient se mettre dans le menu donné
     *
     * @return bool false si aucun manager de setter
     */
    private function _set_update_manager()
    {
	$ok = false;

	if( $this->get_config_value( 'update_by_user' ) )
	{
	    $datas['rules'] = $this->_rule_manager->get_redirections( true );

	    $admin_screen = new AdminScreen(  // devrait etre dans un fichier de configa! // PAS BIEN this devrait fournir la config general et la methode pour y acceder, ok en args deux un array de config pour la classe ==> CONFUS !!
		array( 'page_title' => 'Redirect',
		'menu_title' => 'Redirect', 
		'capability' => 'manage_options',
		'menu_slug' => 'redirect_menu',
		'template_name' => 'redirect',
		'template_path' => $this->_config['template_path'],
		'img_url' => $this->_config['img_url'], // stupide, devrait etre fournit par une methode qui gere la config general. faut un jeux de composant parent qui se charge des taches de routine et de la coherence et la non redondance
		'js_url' => $this->_config['js_url'], // stupide, devrait etre fournit par une methode qui gere la config general. faut un jeux de composant parent qui se charge des taches de routine et de la coherence et la non redondance
		'icon_name' => 'button_html.png',
		'position' => '6',
		'datas' => $datas,
		'parent_menu' => $this->_config['parent_menu']
	    ) );

	    $admin_handler = new AdminHandler( $this ); // BIEN
	    $ok = true;
	}

	return $ok;
    }

    /**
     * Effectue la redirection si celle ci existe
     *
     * @param string $origin url de base
     * @param bool $redirect effectue ou non une redirection
     *
     * @return void
     */
    public function execute( $origin = false, $redirect = true )
    {
	if( ! $origin )
	{
	    if( array_key_exists( 'REQUEST_URI', $_SERVER ) )
	    {
		$origin = $_SERVER['REQUEST_URI'];
	    }
	}

	$infos_redirect = $this->_rule_manager->get_redirection( $origin );
	if( $infos_redirect && $redirect )
	{
	    wp_redirect( $infos_redirect['bound_to'], $infos_redirect['code'] ); 
	    exit;
	}

	return $infos_redirect;
    }
}
