<?php
/**
 * handles Event items in database
 * @since 1.1.0
 * @author: Brian Sharp
 */
class crmPluginEvents
{	
	
	static function init()
	{		
		
		/* add Ticket Event to database */
		add_action('wp_ajax_crm_spi_addTicket',function(){			
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";	
			$wpdb->insert( 
				$tbl_events, 
				array( 
					"techid" => $_POST['sel_tech'], 
					"customerid" => $_POST['sel_customer'],  
					"locationid" => $_POST['sel_location'] , 
					"isremote" => $_POST['rb_service'] ,
					"supporttypeid" => $_POST['ddlSupType']  ,
					"timein" => date( 'Y-m-d H:i:s', strtotime($_POST['start_date'] . " " . $_POST['sel_hourin'] . ":" . $_POST['sel_minin'] . " " . $_POST['sel_ampmin'] ) ) ,
					"timeout" => date( 'Y-m-d H:i:s', strtotime($_POST['end_date'] . " " . $_POST['sel_hourout'] . ":" . $_POST['sel_minout'] . " " . $_POST['sel_ampmout'] ) ) ,
					"subject" => $_POST['txt_description'] ,
					"details" => $_POST['txt_resolution'] ,
					"requestedby" => $_POST['txt_contact']  ,
					"ticketcreated" => date('Y-m-d H:i:s'),
					"isbillable" => isset($_POST['cb_billable'])
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%d'
				) 
			);
			print self::getview();
			wp_die();
		});		

		/* Initialize Ticket */
		add_action('wp_ajax_crm_spi_initTicket',function(){			
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";	
			$wpdb->insert( 
				$tbl_events, 
				array( 
					"techid" => $_POST['assign_tech'],
					"customerid" => $_POST['sel_customer'],
					"supporttypeid" => $_POST['ddlSupType'],
					"subject" => $_POST['txt_description'],
					"requestedby" => $_POST['txt_contact'],
					"ticketcreated" => date('Y-m-d H:i:s'),
					"createdby" => get_current_user_id()
				),
				array(
					'%d',
					'%d',
					'%d',
					'%s', 
					'%s', 
					'%s',
					'%d'
				) 
			);
			print self::getview('dashboard.php');
			wp_die();
		});		
		

		
		/* Delete Event */
		add_action('wp_ajax_crm_spi_delTicket',function(){

				//print $_POST['ticketid']; wp_die();
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";
			$wpdb->delete( $tbl_events, array( "id" => $_POST['ticketid'] ), array( '%d' ) );
			print self::getview("default.php");
			wp_die();
		});		

		/* Update Ticket  */
		add_action('wp_ajax_crm_spi_updTicket',function(){
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";		
				//echo $_POST['sel_tech'].", ".$_POST['sel_customer'].", ".$_POST['sel_location']; wp_die();
			$wpdb->update( 
				$tbl_events,  
				array( 
					"techid" => $_POST['sel_tech'], 
					"customerid" => $_POST['sel_customer'],  
					"locationid" => $_POST['sel_location'] , 
					"isremote" => $_POST['rb_service'] ,
					"supporttypeid" => $_POST['sel_suptype']  ,
					"timein" => date( 'Y-m-d H:i:s', strtotime($_POST['start_date'] . " " . $_POST['sel_hourin'] . ":" . $_POST['sel_minin'] . " " . $_POST['sel_ampmin'] ) ) ,
					"timeout" => date( 'Y-m-d H:i:s', strtotime($_POST['end_date'] . " " . $_POST['sel_hourout'] . ":" . $_POST['sel_minout'] . " " . $_POST['sel_ampmout'] ) ) ,
					"subject" => $_POST['txt_description'] ,
					"details" => $_POST['txt_resolution'] ,
					"requestedby" => $_POST['txt_contact']  ,
					"isbillable" => $_POST['rb_billable']
				),
				array( 'id' => $_POST['txt_id'] ), 
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%d' 
				)
			);
			print self::getview($_POST['dashboard']);
			wp_die();
		});			
	}	
	
	static function getview($viewName = 'default.php'){
		$data = new stdClass();
		isset($data->page) || isset($_POST['page']) ?: $data->page = 1; //this looks sketchy
		return cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
	}
	
	static function security(){
			return crmPluginSecurity::security("");	
	}

}