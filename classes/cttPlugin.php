<?php
/**
 * Class cttPlugin is called whenever a do_action tag is come across.
 * Responsible for outputting the plugin's HTML, CSS and Javascript.
 *
 * @since 1.0.0
 * @author: Brian Sharp
 */
class cttPlugin
{	


	/**
	 * Function deploy prints out the prepared html
	 *
	 * @since 1.0.0
	 */
	static function deploy()
	{
		echo self::prepare();
	}
	

	
	/**
	 * Function prepare returns the required html and enqueues
	 *
	 * @since 1.0.0
	 * @return String $output
	 */
	static function prepare()
	{	
		$uid = get_current_user_id();
		global $wpdb; 
		$tbl_users = $wpdb->prefix . "issupportmembers";
		$row = $wpdb->get_row( "SELECT * FROM $tbl_users WHERE userid= $uid;" );
		
		//check if user exists and auto deny anyone not added to logs
		$userexists =  $wpdb->get_var("select count(*) from $tbl_users WHERE userid= $uid");		
		if ($userexists == 0 ){
			cttPluginUsers::ctt_create_user($uid);
			$row = $wpdb->get_row( "SELECT * FROM $tbl_users WHERE userid= $uid;" );
		}	
		
		if (!$row->isactive){
			$viewName = "denied.php";
			$output = cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName);	
			return $output;
		}
		
		
		else{		
			$data               = new stdClass();
			$data->view      	= 'dashboard.php';
			$viewName = null;			
			// Include output file to store output in $output.
			$viewName = "wrapper.php";
			//$viewName = "default.php";
			$data->security = true;
			//$data->ticketid = 3301;

			$output = cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
			return $output;
		}
	}

}