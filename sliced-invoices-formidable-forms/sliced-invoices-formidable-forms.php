<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Sliced Invoices & Formidable Forms
 * Plugin URI:        https://slicedinvoices.com/extensions/formidable-forms
 * Description:       Create forms that allow users to submit a quote or estimate request. Requirements: The Sliced Invoices Plugin and Formidable Forms
 * Version:           1.0
 * Author:            Sliced Invoices
 * Author URI:        https://slicedinvoices.com/
 * Text Domain:       sliced-invoices-formidable-forms
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $formidablePro;
$formidablePro = false;

require_once( 'includes/class-sliced-invoices-ff.php' );

/*
 * Install Plugin 
 */
register_activation_hook(__FILE__, 'sliced_ff_table_install');
function sliced_ff_table_install() {
    
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $table = $wpdb->prefix."siff_settings";
    if($wpdb->get_var("show tables like '$table'") != $table){     
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
    			  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `form_id` INT(11) NOT NULL,
                  `enable` tinyint(2) NOT NULL,
                  `type` varchar(50) NOT NULL,
                  `name` int(11) NOT NULL,
                  `email` int(11) NOT NULL,
                  `business_name` int(11) NOT NULL,
                  `business_address` int(11) NOT NULL,
                  `extra_info` int(11) NOT NULL,
                  `website` int(11) NOT NULL,                  
                  `order_number` int(11) NOT NULL,                  
                  `title` int(11) NOT NULL,
                  `description` int(11) NOT NULL,
                  `terms` int(11) NOT NULL,
                  `tax_rate` int(11) NOT NULL,
                  `date` int(11) NOT NULL,
                  `line_item` int(11) NOT NULL,
                  `line_item_meta` VARCHAR(200) NOT NULL,
                  `status` varchar(50) NOT NULL,
                  `send_to_client` tinyint(2) NOT NULL,
                  `added_on` datetime NOT NULL,
                  `added_by` int(11) NOT NULL,
    			  PRIMARY KEY (`id`)			  
    			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    	dbDelta($sql);
    }    
}

/*
 * Requirements check
 */
add_action( 'init', 'sliced_ff_validate_settings' );

function sliced_ff_requirements_not_met_notice_sliced() {
    
	echo '<div id="message" class="error">';
	echo '<p>' . sprintf( __( 'Sliced Invoices & Formidable Forms extension cannot find the required <a href="%s">Sliced Invoices plugin</a>. Please make sure the core Sliced Invoices plugin is <a href="%s">installed and activated</a>.', 'sliced-invoices-formidable-forms' ), 'https://wordpress.org/plugins/sliced-invoices/', admin_url( 'plugins.php' ) ) . '</p>';
	echo '</div>';    
}

function sliced_ff_requirements_not_met_notice_ff() {
    
	echo '<div id="message" class="error">';
	echo '<p>' . sprintf( __( 'Sliced Invoices & Formidable Forms extension cannot find the required <a href="%s">Formidable Forms plugin</a>. Please make sure the Formidable Forms plugin is <a href="%s">installed and activated</a>.', 'sliced-invoices-formidable-forms' ), 'https://wordpress.org/plugins/formidable/', admin_url( 'plugins.php' ) ) . '</p>';
    
	echo '</div>';
}

/*
 * Active Sliced Invoices Settings For Formidable
 */
function sliced_ff_validate_settings() {
    
    $sliced = $formi = 1;
	if ( ! class_exists( 'Sliced_Invoices' ) ) {
		add_action( 'all_admin_notices', 'sliced_ff_requirements_not_met_notice_sliced' );
        $sliced = 0;
	}
	
	if ( ! class_exists( 'FrmHooksController' ) ) {
		add_action( 'all_admin_notices', 'sliced_ff_requirements_not_met_notice_ff' );
        $formi = 0;
	}
    
    global $wpdb, $formidablePro;
    
    if(class_exists('FrmProInstallPlugin')) $formidablePro = true;    
    
    sliced_ff_define( 'SIFF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    sliced_ff_define( 'SIFF_AJAX_LOADER', SIFF_PLUGIN_URL. '/assets/images/ajax-small-loader.gif' );    
	sliced_ff_define( 'SIFF_WP_PREFIX', $wpdb->prefix );
	sliced_ff_define( 'SIFF_SETTINGS_TABLE', SIFF_WP_PREFIX.'siff_settings' );
    sliced_ff_define( 'SIFF_FRM_FORMS', SIFF_WP_PREFIX.'frm_forms' );
    sliced_ff_define( 'SIFF_FRM_FORM_FIELDS', SIFF_WP_PREFIX.'frm_fields' );
    
    add_action('admin_enqueue_scripts', 'sliced_ff_enqueue_admin');
        
    if($sliced == 1 && $formi == 1){
        add_filter( 'frm_add_form_settings_section', array( 'Sliced_Invoices_FF_Load', 'sliced_ff_load' ), 5 );
        add_action( 'frm_update_form', array( 'Sliced_Invoices_FF_Load', 'sliced_ff_update_settings' ), 10, 2 );
        add_action( 'frm_process_entry', array( 'Sliced_Invoices_FF_Load', 'sliced_ff_after_frm_process' ), 10, 1 );       
    }
}

function sliced_ff_enqueue_admin($hook){
    
    if($hook == 'toplevel_page_formidable'){
        wp_register_style('siff-style', SIFF_PLUGIN_URL . '/assets/css/sliced-ff-style.css',array(),'1.0');
        wp_enqueue_style('siff-style');
        wp_register_script( 'siff-js', SIFF_PLUGIN_URL . '/assets/js/sliced-ff-custom.js',array(),'1.0' );
    	wp_enqueue_script( 'siff-js' ); 
    }
}

function sliced_ff_define( $name, $value ) {
        
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}


/*
 * Returns the main instance of SIFF.
 */
function SIFF() {    
	return Sliced_Invoices_FF::siff_instance();
}
$GLOBALS['sliced-invoices-formidable-forms'] = SIFF(); 


/*
 * Sliced Invoices Call functions
 */
class Sliced_Invoices_FF_Load {
    
	public static function sliced_ff_load($sections) {
	   		        
        return SIFF()->sliced_ff_settings($sections);        		
	}
    
    public static function sliced_ff_update_settings($form_id, $values) {
	    
        if(isset($values['sliced_ff_type'])){
            SIFF()->sliced_ff_save_settings($form_id, $values);    		
        }                       
	}
    
    public static function sliced_ff_after_frm_process($params) {
        
        if(isset($params['action']) && $params['action'] == 'create') {            
            if(isset($params['form_id']) && $params['form_id'] != "" && isset($params['id']) && $params['id'] != "") {
                SIFF()->sliced_ff_create($params);                    
            }
        }
    }    
}


/*
 * Get fields added under repeater field
 */
add_action('wp_ajax_sliced_ff_field_meta', 'sliced_ff_field_meta');

function sliced_ff_field_meta(){
    
    $repeater      = SIFF()->sliced_ff_repeater_fields($_POST['id']);    
    $fields        = array();
    $fields['qty'] = $fields['amt'] = $fields['title'] = $fields['desc'] = "<option value=''>--Choose Field--</option>";    
    foreach($repeater as $rep){                
        if($rep->type == "number" || $rep->type == "hidden"){
            $fields["qty"] .= "<option value='".$rep->id."'>".$rep->name."</option>";
            $fields["amt"] .= "<option value='".$rep->id."'>".$rep->name."</option>";   
        }
        if($rep->type == "text" || $rep->type == "hidden"){
            $fields["title"] .= "<option value='".$rep->id."'>".$rep->name."</option>";
            $fields["desc"]  .= "<option value='".$rep->id."'>".$rep->name."</option>";  
        }
        if($rep->type == "textarea" || $rep->type == "hidden"){
            $fields["desc"] .= "<option value='".$rep->id."'>".$rep->name."</option>"; 
        }
    }     
    echo json_encode($fields);  
    die();
}