<?php
/**
 * Class crmPluginAjax is used to register AJAX functions
 * as soon as possible, so they only leave a light footprint.
 *
 * @since 1.1.0
 * @author: Brian Sharp
 */
class crmPluginUsers
{
	/**
	 * Called as early as possible to be able to have as light as possible AJAX requests. Hooks can be added here as to
	 * have early execution.
	 *
	 * @since 1.1.0
	 */
	static function init()
	{
		/* Update support name and/or support enabled */
		add_action('wp_ajax_crm_spi_updateUser',function(){


			//print $_POST['userid'];
			
			$uid = $_POST['userid'];
			
			global $wpdb; 
			$tbl_users = $wpdb->prefix . "issupportmembers";			
						
			//$activechecked = false;
			//if (isset($_POST['supportisactive'])){$activechecked = true;};	
			
			$a = false;
			if (isset($_POST['isactive'])){$a = true;}
			$b = false;
			if (isset($_POST['editusers'])){$b = true;}
			$c = false;
			if (isset($_POST['editsupportcategores'])){$c = true;}
			$d = false;
			if (isset($_POST['editcustomers'])){$d = true;}
			$e = false;
			if (isset($_POST['superuser'])){$e = true;}
			$f = false;
			if (isset($_POST['editevents'])){$f = true;}
			$g = false;
			if (isset($_POST['viewreports'])){$g = true;}			
						
			$wpdb->update( 
				$tbl_users,  
				array( 
				"isactive" => $a,
				"editusers" => $b,
				"editsupportcategores" => $c,
				"editcustomers" => $d,  
				"superuser" => $e, 
				"editevents" =>  $f, 
				"viewreports" =>  $g
				),
				array( 'userid' => $uid ), 
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d'
				)
			);

			print self::getview();
			wp_die();
		});
	}
	
	static function ctt_create_user($uid){			
		global $wpdb; 
		$tbl_users = $wpdb->prefix . "issupportmembers";	
		$wpdb->insert( 
			$tbl_users, 
			array( 
				"userid" =>  $uid, 
				"isactive" => true,  
				"superuser" => 0, 
				"editusers" => 0,
				"editsupportcategores" => 0,
				"editcustomers" => 0,
				"editevents" =>  0,
				"viewreports" =>  0,
				"viewevents" => 0
			),				
			array(
				'%d',
				'%d',
				'%d',
				'%d',
				'%d', 
				'%d',
				'%d',
				'%d', 
				'%d' 
			) 
		);
	}	
	
	static function getview(){
		$data = new stdClass();
		$data->security = true;
		$viewName = "users.php";		
		return cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
		
	}
	
	static function security(){
			return crmPluginSecurity::security("editcustomers");
	
	}
}