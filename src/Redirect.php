<?php
namespace DenDev\Plpwpredirect;
use DenDev\Plpwpredirect\RedirectInterface;
use DenDev\Plpwpredirect\Lib\DBRuleManager;
use DenDev\Plpwpredirect\Lib\AdminScreen;


/**
 *  Redirect 
 */
class Redirect implements RedirectInterface
{
    /** @var object lib for manage rule */
    private $_rule_manager;


    /**
     * Set le kernel du servie 
     *
     * @param object $krl la ref du kernel auquel appartient le service ou false par defaut.
     *
     * @return void
     */
    public function __construct( $krl = false, $args = array() )
    {
	// parent::__construct( $krl, $args );
	$this->_args = $args;
	$this->_config = $this->get_default_configs(); // TODO adaptability doit prendre le relais
	$this->_set_rule_manager();
	if( $this->get_args_value( 'set_update_manager' ) )
	{
	    $this->_set_update_manager();
	}
    }

    /**
     * Traite le tableau d'argument donnes a l'object
     *
     * @param string $arg_name nom de l'argument dont on veut la valeur 
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
     * Configuration par defaut du service
     *
     * @var string $storage db,file,htacess 
     *
     * @return array tableau associatif option value.
     */
    public function get_default_configs()
    {
	return array( 
	    //'root_path' => 
	    'storage' => 'db',
	    'db_table_name' => 'dwredirect',
	    'file_name' => 'fake_htaccess.php',
	    'file_path' => '',
	    'update_by_user' => true,
	    'update_by_code' => true,
	    'update_by_permalink' => true,
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
     * Permet a l'utilisateur de cree ses redirction via une interface
     * Permet la creation automatique de redirection si on change le permalien
     * Les choix d'actions dependant de la config update_by_*
     *
     * @return bool false si aucun manager de setter
     */
    private function _set_update_manager()
    {
	$ok = false;

	if( $this->get_config_value( 'update_by_user' ) )
	{
	    $admin_screen = new AdminScreen();
	    $ok = true;
	}

	return $ok;
    }

    public function execute( $origin )
    {
	$infos_redirect = $this->_rule_manager->get_redirection( $origin );
	if( $infos_redirect )
	{
	    wp_redirect( $infos_redirect['bound_to'], $infos_redirect['code'] ); 
	    exit;
	}
    }
}
