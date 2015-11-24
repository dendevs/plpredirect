<?php 
namespace DenDev\Plpwpredirect\Lib;


/**
 * Gere les regles en utilisant la db.
 *
 * La db utiliser est celle de wp. 
 * La gestion des regles est leur creation, verif, save et recuperation
 * Sert a travailler dans l'environnement db
 */
class DBRuleManager
{
    /** @var string nom de la table en db */
    private $_table_name;


    /**
     * Constructor
     *
     * se contente d'instancier la lib
     */
    public function __construct( $table_name )
    {
	$this->_table_name = $table_name;
	$this->create_table();
    }

    /**
     * Cree une nouvelle table dans wp
     *
     * @return bool true si la table est cree
     */
    public function create_table()
    {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $this->_table_name (
	    id mediumint(9) NOT NULL AUTO_INCREMENT,
	    origin VARCHAR( 255 ) NOT NULL,
	    bound_to VARCHAR( 255 ) NOT NULL,
	    code VARCHAR(5) DEFAULT '301' NOT NULL,
	    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	    UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$tmp = dbDelta( $sql );

	return ( array_key_exists( $this->_table_name, $tmp ) ) ? true : false;
    }

    /**
     * Cree une regle de redirection
     *
     * Ajoute la regle en db 
     *
     * @param string $origin url d'origin 
     * @param string $bound_to url de redirection
     * @param string $code code de redirection par defaut 301
     *
     * @return bool false si echec
     */
    public function add_redirection( $origin, $bound_to, $code = '301' )
    {
	$ok = false;
	// TODO check url exist

	global $wpdb;

	// already exist
	$results = $wpdb->get_results( $wpdb->prepare( 
	    "
		SELECT origin 
		FROM $this->_table_name
		WHERE origin = '%s'
	    ",
	    $origin
	) );

	// add in db
	if( empty( $results ) )
	{
	    $ok = $wpdb->query( $wpdb->prepare( 
	    "
		INSERT INTO $this->_table_name
		( origin, bound_to, code )
		VALUES ( %s, %s, %s )
	    ", 
	    $origin, 
	    $bound_to, 
	    $code 
	    ) );
	}
	else // update 
	{
	    $ok = $wpdb->query( $wpdb->prepare( 
	    "
		UPDATE $this->_table_name
		SET bound_to = '%s', code = '%s'
		WHERE origin = '%s'
	    ", 
	    $bound_to, 
	    $code ,
	    $origin 
	    ) );
	}

	return ( $ok === false ) ? false : true;
    }

    /**
     * Donne l'url de redirection d'une url d'origin
     *
     * @param string origin l'url d'origin
     *
     * @return array tableau associatif code, bound_to  ou false
     */
    public function get_redirection( $origin )
    {
	global $wpdb;
	$ok = false;

	$results = $wpdb->get_results( $wpdb->prepare( 
	    "
		SELECT code, bound_to 
		FROM $this->_table_name 
		WHERE origin = '%s'
		ORDER BY date DESC
	    ",
	    $origin
	) );

	if( ( bool ) $results ) // better perfs than is_empty
	{
	    $code = $results[0]->code;
	    $bound_to = $results[0]->bound_to;

	    $ok = array();
	    $ok[$code] = $bound_to;
	}

	return $ok;
    }
} 
