<?php
/**
 * handles Event items in database
 * @since 1.1.0
 * @author: Brian Sharp
 */
class crmPluginRender
{	
	
	static function init()
	{		
			
		/* Returns view */
		add_action('wp_ajax_crm_spi_getView',function(){

	
			$data = new stdClass();
			$data->security = true;
			$data->view = $_POST['view'] . ".php";
//print 	$data->view; //wp_die();		
			isset($_POST['page']) ? $data->page = $_POST['page']   : 	$data->page = 1;
//print 	"POST[page] is $_POST[page]  AND data->page is  $_POST[page]"; //wp_die();	
			!isset($_POST['uid']) 		?: 	$data->uid = $_POST['uid'];
//print 	"POST[uid] is  $_POST[uid]  AND data->uid is  $_POST[uid]"; wp_die();
			!isset($_POST['ticketid']) 	?: 	$data->ticketid = $_POST['ticketid'];
			!isset($_POST['filters']) 	?: 	$data->filters = $_POST['filters'];
//print 	"POST[ticketid] is $_POST[ticketid] AND data->ticketid is  $_POST[ticketid]"; wp_die();
			
//print $view; wp_die();
			$_POST['view'] == "dashboard" || $_POST['wrap_view'] == 'true' ? $view = 'wrapper.php' :$view = $_POST['view'] . ".php" ;

			print cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $view, $data);	
			wp_die();
		});	
	}
		
	
	static function security(){
			return crmPluginSecurity::security("editcustomers");	
	}

}