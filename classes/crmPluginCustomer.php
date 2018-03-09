<?php
/**
 * handles Event items in database
 * @since 1.0.0
 * @author: Brian Sharp
 */
class crmPluginCustomer
{	
	
	static function init()
	{		
			
		/* add new customer */
		add_action('wp_ajax_crm_spi_addCustomer',function(){			
			global $wpdb;
			$tbl_loc = $wpdb->prefix . "customerlocation";
			$tbl_cus = $wpdb->prefix . "customers";
			$wpdb->insert( 
				$tbl_cus ,  
				array( 
					"customername" => $_POST['txt_newcustomer'],  
					"isactive" => true
				),
				array(
					'%s',
					'%d'
				)
			);
			$lastid = $wpdb->insert_id;					
			$wpdb->insert( 
				$tbl_loc,  
				array( 
					"customerid" => $lastid,  
					"customerlocation" => $_POST['txt_newlocation'],  
				),
				array(
					'%d',
					'%s'
				)
			);
		
			print self::getview();
			wp_die();
		});		

		/* Delete customer */
		add_action('wp_ajax_crm_spi_delCustomer',function(){			
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events"; 
			$tbl_cus = $wpdb->prefix . "customers";
			$tbl_loc = $wpdb->prefix . "customerlocation";	
			$rowcount = $wpdb->get_var('SELECT count(*) FROM ' . $tbl_events . ' WHERE customerid = ' . $_POST["customerid"]);
			if ($rowcount>0){ print json_encode(
				array(
					'error' => 'Cannot delete a customer associated with a support ticket.  Either delete all associated tickets or reassign the tickets&#39; customers before proceeding.',
					'element' => '#feedback' . $_POST['customerid']					
			));} else {
				$wpdb->delete( $tbl_loc, array( "customerid" => $_POST['customerid'] ) );
				$wpdb->delete( $tbl_cus, array( "id" => $_POST['customerid'] ) );
				print self::getview();
			}	
			wp_die();
		});		

		/* Update customer name and/or customer enabled */
		add_action('wp_ajax_crm_spi_rnmCustomer',function(){
			//print implode(" ", $_POST);			
			global $wpdb; 
			$tbl_cus = $wpdb->prefix . "customers";
			$activechecked = false;
			if (isset($_POST['customerisactive'])){$activechecked = true;};	
			$wpdb->update( 
				$tbl_cus,  
				array( 
					"customername" => $_POST['txt_customer'],  
					"isactive" => $activechecked
				),
				array( 'id' => $_POST['customerid'] ), 
				array(
					'%s',
					'%d'
				)
			);
			print self::getview();
			wp_die();
		});		
		
		/* add new customer location */
		add_action('wp_ajax_crm_spi_addLocation',function(){			
			global $wpdb; 
			$tbl_loc = $wpdb->prefix . "customerlocation";				
			$wpdb->insert( 
				$tbl_loc,  
				array( 
					"customerid" =>  $_POST['parentid'],  
					"customerlocation" => $_POST['newlocation_li'] 
				),
				array(
					'%d',
					'%s'
				)
			);	
	
			$data = new stdClass();
			$viewName = "customer.php";		
			print cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);		

			wp_die();
		});		
					
		/* delete customer location */
		add_action('wp_ajax_crm_spi_deleteLocation',function(){
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events"; 
			$tbl_loc = $wpdb->prefix . "customerlocation";	
			$rowcount = $wpdb->get_var('SELECT count(*) FROM ' . $tbl_events . ' WHERE locationid = ' . $_POST['locationid']);
			$loccount = $wpdb->get_var('SELECT count(*) FROM ' . $tbl_loc . ' WHERE customerid = ' . $_POST['customerid']);
			
			if ($rowcount>0){
				print json_encode(
					array(
						'error' => 'Cannot delete a location associated with an event.',
						'element' => '#feedback' . $_POST['customerid']					
					));
				wp_die();}  
			elseif ($loccount < 2) {
				print json_encode(
					array(
						'error' => 'A customer must have at least 1 location.',
						'element' => '#feedback' . $_POST['customerid']					
					));
				wp_die();}
			else {
				$wpdb->delete( $tbl_loc, array( "id" => $_POST['locationid'] ) );
				$data = new stdClass();
				$viewName = "customer.php";		
				print cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
				wp_die();
			}
		});		
			
		/* rename customer location */
		add_action('wp_ajax_crm_spi_renameLocation',function(){			
			global $wpdb; 
			$tbl_loc = $wpdb->prefix . "customerlocation";			
			$wpdb->update( 
				$tbl_loc,  
				array( 
					"customerlocation" => $_POST['txt_location_li'],  
				),
				array( 'id' => $_POST['locationid'] ), 
				array(
					'%s',
				)
			);
	
			$data = new stdClass();
			$viewName = "customer.php";		
			print cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);			
				wp_die();
			}
		);				
			
	}	
	
	static function getview(){
		$data = new stdClass();
		$data->security = true;
		$viewName = "customer.php";		
		return cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
		
	}
	
	static function security(){
			return crmPluginSecurity::security("editcustomers");
	
	}

}