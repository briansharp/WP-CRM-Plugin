<?php
/**
 * Class crmPluginAjax is used to register AJAX functions
 * as soon as possible, so they only leave a light footprint.
 *
 * @since 1.1.0
 * @author: Brian Sharp
 */
class crmPluginSupportCat
{
	/**
	 * Called as early as possible to be able to have as light as possible AJAX requests. Hooks can be added here as to
	 * have early execution.
	 *
	 * @since 2.0.0
	 */
	static function init()
	{
		//add_action('wp_ajax_slideshow_jquery_image_gallery_load_stylesheet', array('SlideshowPluginSlideshowStylesheet', 'loadStylesheetByAJAX'));
		//add_action('wp_ajax_nopriv_slideshow_jquery_image_gallery_load_stylesheet', array('SlideshowPluginSlideshowStylesheet', 'loadStylesheetByAJAX'));

		/* add new Support Category] */
		add_action('wp_ajax_crm_spi_addSupport',function(){
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";			
			$wpdb->insert( 
				$tbl_sup,  
				array( 
					"supporttype" => $_POST['newsupportitem'],  
					"isactive" => true
				),
				array(
					'%s',
					'%d'
				)
			);	
			print self::getview();
			wp_die();
		});		

		/* Delete support */
		add_action('wp_ajax_crm_spi_delSupport',function(){
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";
			$tbl_events = $wpdb->prefix . "events";	
			$rowcount = $wpdb->get_var('SELECT count(*) FROM ' . $tbl_events . ' WHERE supporttypeid = ' . $_POST["supportid"]);
			if ($rowcount>0){ print json_encode(
				array(
					'error' => 'Cannot delete a ticket category associated with a support ticket.  Either delete all associated tickets or reassign the tickets&#39; support category before proceeding.',
					'element' => '#feedback' . $_POST['supportid']					
			));} else {
				$wpdb->delete( $tbl_sup, array( "id" => $_POST['supportid'] ) );
				print self::getview();
			}	
			wp_die();
		});		

		/* Update support name and/or support enabled */
		add_action('wp_ajax_crm_spi_renameSupport',function(){
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";
			$activechecked = false;
			if (isset($_POST['supportisactive'])){$activechecked = true;};	
			$wpdb->update( 
				$tbl_sup,  
				array( 
					"supporttype" => $_POST['txt_support'],
					"isactive" => $activechecked
				),
				array( 'id' => $_POST['supportid'] ), 
				array(
					'%s',
					'%d'
				)
			);

			print self::getview();
			wp_die();
		});		
		
		
			
	}	
	
	static function getview(){
		$data = new stdClass();
		$data->security = true;
		$viewName = "supporttypes.php";		
		return cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);	
		
	}
	
	static function security(){
			return crmPluginSecurity::security("editcustomers");
	
	}
}