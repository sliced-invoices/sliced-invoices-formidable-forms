<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sliced_Invoices_FF {        
    
    protected static $_siff_instance = null;
        
    public static function siff_instance() {
        
		if ( is_null( self::$_siff_instance ) ) {
			self::$_siff_instance = new self();
		}
		return self::$_siff_instance;
	} 
    
    public function sliced_ff_settings($sections){
        
        $main_form = $this->sliced_ff_if_main_form();
        if($main_form){
            $sections['sliced-invoices'] = array(
        				'name'     => __( 'Sliced Invoices', 'sliced-invoices-formidable-forms' ),
        				'function' => array( __CLASS__, 'sliced_ff_setting_details' ),
        				'id'       => 'sliced_ff_settings',
        				'icon'     => 'frm_icon_font frm_file_icon',
        			);
        }
        return $sections;            
    }
    
    public function sliced_ff_select($query, $row = true){
        
        global $wpdb;
        if($row)
            return $wpdb->get_row($query);
        else
            return $wpdb->get_results($query);
    }
    
    public function sliced_ff_update($table, $data, $where){
        
        global $wpdb;
        $wpdb->update($table, $data, $where);
    }
    
    public function sliced_ff_insert($table, $data){
        
        global $wpdb;
        $wpdb->insert($table, $data);
    }
    
    public function sliced_ff_if_main_form(){
        
        if(isset($_GET['id'])){
            $form = $this->sliced_ff_form_details($_GET['id']);
            if(isset($form->parent_form_id) && $form->parent_form_id == 0)
                return true;                
        }
        return false;         
    }
    
    public function sliced_ff_form_details($Fid){
        
        return $this->sliced_ff_select("SELECT * FROM " . SIFF_FRM_FORMS . " WHERE id = " . $Fid);  
    }    
    
    public function sliced_ff_form_fields(){
        
        //'text','email','textarea','number','rte','url','hidden' ['date',divider - pro]
        return $this->sliced_ff_select("SELECT id,name,type FROM " . SIFF_FRM_FORM_FIELDS . " WHERE form_id = " . $_GET['id'] ." AND type IN ('text','email','textarea','number','rte','url','hidden','date','divider')",false);  
    }
    
    public function sliced_ff_repeater_fields($id){
        
        $fields = array();
        $form = $this->sliced_ff_select("SELECT * FROM ".SIFF_FRM_FORM_FIELDS." WHERE id = ".$id);        
        if($form){        
            if(is_object($form->field_options) || is_array($form->field_options)) $form_opt = $form->field_options;
            else $form_opt = maybe_unserialize($form->field_options);
            if(isset($form_opt['form_select'])){
                $fields = $this->sliced_ff_select("SELECT id,name,type FROM ".SIFF_FRM_FORM_FIELDS." WHERE form_id = ".$form_opt['form_select'],false);
            }        
        }
        return $fields;
    }
        
    public function sliced_ff_settings_data($form_id = 0){
        
        return $this->sliced_ff_select("SELECT * FROM ".SIFF_SETTINGS_TABLE." WHERE form_id = ".($form_id == 0 ? $_GET['id'] : $form_id));  
    }
    
    public function sliced_ff_setting_details(){
        
        $siff_field = $siff_values = array();
        $siff_form_fields = SIFF()->sliced_ff_form_fields();        
        $siff_values      = SIFF()->sliced_ff_settings_data();
        if($siff_form_fields){
            foreach($siff_form_fields as $sField){
                if(!isset($siff_field[$sField->type])) $siff_field[$sField->type] = "";
                $siff_field[$sField->type] .= '<option value="'.$sField->id.'">'.$sField->name.'</option>';    
            }    
        }
        
        //get fields for repeater (Formidable Pro)
        $qty = $amt = $title = $desc = "";
        if(isset($siff_values->line_item) && !empty($siff_values->line_item)){
            $repeater = SIFF()->sliced_ff_repeater_fields($siff_values->line_item);
            $si_linemeta = maybe_unserialize($siff_values->line_item_meta);            
            foreach($repeater as $rep){                
                if($rep->type == 'number' || $rep->type == 'hidden'){
                    $qty .= '<option value="'.$rep->id.'"'.(isset($si_linemeta['qty']) && $rep->id == $si_linemeta['qty'] ? ' selected="selected"' : "").'>'.$rep->name.'</option>';
                    $amt .= '<option value="'.$rep->id.'"'.(isset($si_linemeta['amt']) && $rep->id == $si_linemeta['amt'] ? ' selected="selected"' : "").'>'.$rep->name.'</option>';   
                }
                if($rep->type == 'text' || $rep->type == 'hidden'){
                    $title .= '<option value="'.$rep->id.'"'.(isset($si_linemeta['title']) && $rep->id == $si_linemeta['title'] ? ' selected="selected"' : "").'>'.$rep->name.'</option>';
                    $desc  .= '<option value="'.$rep->id.'"'.(isset($si_linemeta['desc']) && $rep->id == $si_linemeta['desc'] ? ' selected="selected"' : "").'>'.$rep->name.'</option>';  
                }
                if($rep->type == 'textarea' || $rep->type == 'hidden'){
                    $desc .= '<option value="'.$rep->id.'"'.(isset($si_linemeta['desc']) && $rep->id == $si_linemeta['desc'] ? ' selected="selected"' : "").'>'.$rep->name.'</option>'; 
                }
            }
        }
        
        include('sliced-invoices-ff-settings.php');
    }
    
    public function sliced_ff_save_settings($form_id, $values){
        global $formidablePro, $user_ID;        
        $getFsettings = SIFF()->sliced_ff_settings_data($form_id);
        $details = array(
                'enable'            => (isset($values['sliced_ff_enable']) ? 1 : 0),
                'type'              => $values['sliced_ff_type'],
                'name'              => $values['sliced_ff_cname'],
                'email'             => $values['sliced_ff_cemail'],
                'business_name'     => $values['sliced_ff_cbusiness'],
                'business_address'  => $values['sliced_ff_caddress'],
                'extra_info'        => $values['sliced_ff_cinfo'],
                'website'           => $values['sliced_ff_cwebsite'],                
                'order_number'      => $values['sliced_ff_iorder'],                
                'title'             => $values['sliced_ff_title'],
                'description'       => $values['sliced_ff_description'],
                'terms'             => $values['sliced_ff_terms'],
                'tax_rate'          => $values['sliced_ff_tax'],                
                'status'            => ($values['sliced_ff_type'] == "invoice" ? $values['sliced_ff_istatus'] : $values['sliced_ff_qstatus']),
                'send_to_client'    => $values['sliced_ff_client'],
            );
        
        if($formidablePro){
            $details['date'] = $values['sliced_ff_date'];
            $details['line_item'] = $values['sliced_ff_items'];
            $details['line_item_meta'] = ($values['sliced_ff_items'] != "" ? serialize(array('qty'=>$values['sliced_ff_item_qty'],'title'=>$values['sliced_ff_item_title'],'desc'=>$values['sliced_ff_item_desc'],'amt'=>$values['sliced_ff_item_amt'])) : "");
        }
        else{
            $details['line_item'] = 0;
            $item_meta = array();
            if(is_array($values['sliced_ff_item_qty'])){
                foreach($values['sliced_ff_item_qty'] as $k => $v){
                    $item_meta[$k]['qty']   = $v;
                    $item_meta[$k]['title'] = $values['sliced_ff_item_title'][$k];
                    $item_meta[$k]['desc']  = $values['sliced_ff_item_desc'][$k];
                    $item_meta[$k]['amt']   = $values['sliced_ff_item_amt'][$k];
                }
            }
            $details['line_item_meta'] = (!empty($item_meta) ? serialize($item_meta) : "");
        }
        
        if( isset($getFsettings->added_by) && $getFsettings->added_by != $user_ID && strpos($getFsettings->added_by, $user_ID.",") === false && strpos($getFsettings->added_by, ",".$user_ID) === false ){
            $details['added_by'] = ($getFsettings->added_by == "" ? $user_ID : $getFsettings->added_by.",".$user_ID);    
        }
        
        if(!$getFsettings){
            $details['form_id']  = $form_id;
            $details['added_on'] = date('Y-m-d H:i:s');
            $details['added_by'] = $user_ID;
                                            
            SIFF()->sliced_ff_insert(SIFF_SETTINGS_TABLE, $details);
        }
        else{
            SIFF()->sliced_ff_update(SIFF_SETTINGS_TABLE, $details,array('id'=>$getFsettings->id));    
        }
        
        do_action( 'sliced_invoices_formidable_saved_settings', $form_id, $details );
    }        
    
    public function sliced_ff_create($params){
        
        $if_siff_set = SIFF()->sliced_ff_settings_data($params['form_id']);
        if($if_siff_set && $if_siff_set->enable == 1) {
            
            global $formidablePro;
            $values      = (array) $if_siff_set;
            $post        = $_POST['item_meta'];
            $post_type   = $values['type'];                    
            $line_itemID = 0;                                    
                                    
            $name          = stripslashes(( isset($post[$values['name']]) && $post[$values['name']] != "" ? $post[$values['name']] : "" ));
            $email         = stripslashes(( isset($post[$values['email']]) && $post[$values['email']] != "" ? $post[$values['email']] : "" ));
            $extra_info    = stripslashes(( isset($post[$values['extra_info']]) && $post[$values['extra_info']] != "" ? $post[$values['extra_info']] : ""));
            $website       = stripslashes(( isset($post[$values['website']]) && $post[$values['website']] != "" ? $post[$values['website']] : "" ));		
            $title         = stripslashes(( isset($post[$values['title']]) && $post[$values['title']] != "" ? $post[$values['title']] : "" ));
            $description   = stripslashes(( isset($post[$values['description']]) && $post[$values['description']] != "" ?$post[$values['description']]:""));
            $terms         = stripslashes(( isset($post[$values['terms']]) && $post[$values['terms']] != "" ? $post[$values['terms']] : "" ));
            $tax_rate      = stripslashes(( isset($post[$values['tax_rate']]) && $post[$values['tax_rate']] != "" ? $post[$values['tax_rate']] : 0 ));
            $business_name = stripslashes(( isset($post[$values['business_name']]) && $post[$values['business_name']] != "" ? $post[$values['business_name']] : "" ));
            $address       = stripslashes(( isset($post[$values['business_address']]) && $post[$values['business_address']] != "" ? $post[$values['business_address']] : "" ));                
                
            if($formidablePro){
                $date        = ( isset($post[$values['date']]) && $post[$values['date']] != "" ? strtotime($post[$values['date']]) : "" );
                $line_itemID = $values['line_item'];
            }
            
            if($email != "" && $title != ""){
                
                $status         = $values['status'];
            	$send_to_client = $values['send_to_client'];
                
                
                if($post_type === 'invoice'){
                    $prefix = sliced_get_invoice_prefix();
                    $suffix = sliced_get_invoice_suffix();
            		$order_number = stripslashes(( isset($post[$values['order_number']]) && $post[$values['order_number']] != "" ? $post[$values['order_number']] : "" ));
                    $number = sliced_get_next_invoice_number();
                        
                }else if($post_type === 'quote'){ 
                    $prefix = sliced_get_quote_prefix();
                    $suffix = sliced_get_quote_suffix();
                    $number = sliced_get_next_quote_number();
                }                                          
                            
                $post_array = array(
            		'post_content' => '',
            		'post_title'   => $title,
            		'post_status'  => 'publish',
            		'post_type'    => 'sliced_' . $post_type,
            	);
            
            	// insert post
            	$post_id = wp_insert_post( $post_array, $wp_error = false );
                
                // set status
            	$status = ($status == "" ? 'draft' : $status);
            	$taxonomy = $post_type . '_status';
            	wp_set_object_terms( $post_id, array( $status ), $taxonomy );
                
                /**add meta data */                
                update_post_meta( $post_id, '_sliced_description', $description );                                        
            	update_post_meta( $post_id, '_sliced_' . $post_type . '_created', time() );
            	update_post_meta( $post_id, '_sliced_' . $post_type . '_prefix', esc_html( $prefix ) );
            	update_post_meta( $post_id, '_sliced_' . $post_type . '_number', esc_html( $number ) );
            	update_post_meta( $post_id, '_sliced_' . $post_type . '_suffix', $suffix );
            	update_post_meta( $post_id, '_sliced_number', $prefix . $number . $suffix );
                
                // due or valid until date
            	if($post_type === 'invoice'){
            	    update_post_meta( $post_id, '_sliced_order_number', esc_html( $order_number ) );
                    if($formidablePro) update_post_meta( $post_id, '_sliced_invoice_due', esc_html( $date ) );            		    
            	}
                else if($post_type === 'quote' && $formidablePro){
                    update_post_meta( $post_id, '_sliced_quote_valid_until', esc_html( $date ) );
                }
                                		
            	// quote/inv number for next time
            	if ( $post_type === 'invoice' ) {
            		Sliced_Invoice::update_invoice_number( $post_id );
            	} else if($post_type === 'quote'){
            		Sliced_Quote::update_quote_number( $post_id );
            	}
                
                // tax data
            	$tax = get_option( 'sliced_tax' );
            	update_post_meta( $post_id, '_sliced_tax_calc_method', Sliced_Shared::get_tax_calc_method( $post_id ) );
            	update_post_meta( $post_id, '_sliced_tax', ($tax_rate == 0 ? sliced_get_tax_amount_formatted( $post_id ) : $tax_rate) );
            	update_post_meta( $post_id, '_sliced_tax_name', sliced_get_tax_name( $post_id ) );
            	update_post_meta( $post_id, '_sliced_additional_tax_name', isset( $tax['sliced_additional_tax_name'] ) ? $tax['sliced_additional_tax_name'] : '' );
            	update_post_meta( $post_id, '_sliced_additional_tax_rate', isset( $tax['sliced_additional_tax_rate'] ) ? $tax['sliced_additional_tax_rate'] : '' );
            	update_post_meta( $post_id, '_sliced_additional_tax_type', isset( $tax['sliced_additional_tax_type'] ) ? $tax['sliced_additional_tax_type'] : '' );
                
            	// invoice payment methods
            	if ( $post_type === 'invoice' && function_exists( 'sliced_get_accepted_payment_methods' ) ) {
            		$payment = sliced_get_accepted_payment_methods();
            		update_post_meta( $post_id, '_sliced_payment_methods', array_keys($payment) );
            	}
                
                // add terms
            	if ($terms == ""){
            	    if ( $post_type === 'invoice' ) {
            			$invoices = get_option( 'sliced_invoices' );
            			$terms    = isset( $invoices['terms'] ) ? $invoices['terms'] : '';
            		} else if($post_type === 'quote'){
            			$quotes   = get_option( 'sliced_quotes' );
            			$terms    = isset( $quotes['terms'] ) ? $quotes['terms'] : '';
            		}  
            	}            		
            	if ( $terms != "" ) {
            		update_post_meta( $post_id, '_sliced_' . $post_type . '_terms', $terms );
            	}
                
                // add line items
                if($line_itemID == 0){                    
                    $line_meta  = maybe_unserialize($values['line_item_meta']);
                    $line_items = $si_lineItem = array(); $l = 0;
                    if(is_array($line_meta) && isset($line_meta[0]['qty'])){
                        foreach($line_meta as $li){
                            if(isset($post[$li['title']])) $line_items[$l]['title'] = $post[$li['title']];
                            if(isset($post[$li['desc']])) $line_items[$l]['description'] = $post[$li['desc']];
                            if(isset($post[$li['qty']]) && is_numeric($post[$li['qty']])) $line_items[$l]['qty'] = $post[$li['qty']];
                            if(isset($post[$li['amt']]) && is_numeric($post[$li['amt']])) $line_items[$l]['amount'] = $post[$li['amt']];
                            if(isset($line_items[$l]))$line_items[$l]['taxable'] = 'on';
                            $l++;
                        }                                         
                        if( !empty($line_items) )
                            update_post_meta( $post_id, '_sliced_items', apply_filters( 'sliced_invoices_formidable_line_items', $line_items ) );
                    }                    
                }
                else{
                    $lineItem   = $post[$line_itemID];
                    $line_meta  = maybe_unserialize($values['line_item_meta']);
                    $line_items = array();           
                    if(is_array($line_meta)){                        
                        foreach($lineItem['row_ids'] as $item){
                            foreach($lineItem[$item] as $id => $val){                                
                                if(isset($line_meta['title']) && $line_meta['title'] == $id) $line_items[$item]['title'] = stripslashes($val);
                                else if(isset($line_meta['desc']) && $line_meta['desc'] == $id) $line_items[$item]['description'] = stripslashes($val);
                                else if(isset($line_meta['qty']) && $line_meta['qty'] == $id) $line_items[$item]['qty'] = (is_numeric($val) ? $val : 0);
                                else if(isset($line_meta['amt']) && $line_meta['amt'] == $id) $line_items[$item]['amount'] = (is_numeric($val) ? $val : 0);
                            }
                            if(isset($line_items[$item]))$line_items[$item]['taxable'] = 'on';
                        }                        
                        if( !empty($line_items) )
                            update_post_meta( $post_id, '_sliced_items', apply_filters( 'sliced_invoices_formidable_line_items', $line_items ) );
                    }
                }
                
                //map client with post
                $client_id = null;
        		if ( !email_exists($email) ) {        		          			       			
        			$user_name = $email;
                    $password  = wp_generate_password( 10, true, true );        			
        			$business  = ($business_name != "" ? $business_name : $name);                    
                    if($name != ""){
                        $name  = explode(" ",$name); 
                        $fname = $name[0]; unset($name[0]);
                        $lname = (count($name) >= 1 ? implode(" ",$name) : "");
                    }                    
        			$client_id = wp_create_user( $user_name, $password, sanitize_email( $email ) ); 
                    if(! is_wp_error( $client_id )){
                        if($website != ""){
                            SIFF()->sliced_ff_update(SIFF_WP_PREFIX."users",array("user_url"=>$website),array('id'=>$client_id));
                        }
                        if(isset($fname)) update_user_meta( $client_id, 'first_name', $fname );
                        if(isset($lname)) update_user_meta( $client_id, 'last_name', $lname );       			
            			add_user_meta( $client_id, '_sliced_client_business', wp_kses_post( $business ) );
            			add_user_meta( $client_id, '_sliced_client_address', wp_kses_post( $address ) );
            			add_user_meta( $client_id, '_sliced_client_extra_info', wp_kses_post( $extra_info ) );                        
                    }                    
        		} else {        			
        			$client    = get_user_by( 'email', $email );
        			$client_id = $client->ID;
        		}
        		if ( ! is_wp_error( $client_id ) ) {        			
        			update_post_meta( $post_id, '_sliced_client', (int) $client_id );
        		}
        		
                //send to client                
                if ( $send_to_client == 1 ) {
        			$send = new Sliced_Notifications;
        			if ( $post_type === 'invoice' ) $send->send_the_invoice( $post_id );
        			else if($post_type === 'quote') $send->send_the_quote( $post_id );
        			
        			// set the status again, because send_the_invoice() and send_the_quote() change the status to "sent"
        			wp_set_object_terms( $post_id, array( $status ), $taxonomy );
        		}
                
                do_action( 'sliced_invoices_formidable_processed', $post_id, $client_id, $post, $values );                   
            }                
        }
    }    
}