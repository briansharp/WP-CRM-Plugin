<?php
/*
 Plugin Name: Customer Support Logger
 Plugin URI: 
 Description: Use it to keep track of time spent on customer issues and generate billing reports.  Login to your wordpress account, add a customer if the customer doesn't already exist, create a support issue, input the time spent and resolution.  This plugin has the ability to generate billing reports based on date range, technicion or customer.
 Version: 1.0.0
 Requires at least: 3.5
 Author: Brian Sharp
 Author URI: http://spaceportimaging.com
 License: GPLv2
 Text Domain: customer-time-tracker
*/



/**
 * Class CTTPluginMain fires up the application on plugin load and provides some
 * methods for the other classes to use like the auto-includer and the
 * base path/url returning method.
 *
 * @since 1.1.0
 * @author Brian Sharp
 */
class cttPluginMain
{
	/** @var string $version */
	static $version = '1.1.1';

	/**
	 * Bootstraps the application by assigning the right functions to
	 * the right action hooks.
	 *
	 * @since 1.1.0
	 */
	static function bootStrap()
	{
		self::autoInclude();
		// Initialize localization on init
		//add_action('init', array(__CLASS__, 'localize'));

		// Enqueue hooks
		add_action('wp_enqueue_scripts'   , array(__CLASS__, 'enqueueFrontendScripts'));
		//add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueueBackendScripts'));

		// Initialize ajax listeners as soon as possible for smallest footprint			
		crmPluginCustomer::init();
		crmPluginRender::init();
		crmPluginEvents::init();
		crmPluginSupportCat::init();
		crmPluginUsers::init();
		//crmPluginAjax::init();
		
		
		// Add general settings page
		//cttPluginGeneralSettings::init();

		// Initialize stylesheet builder
		//cttPluginCttStylesheet::init();

		// Deploy on do_action('ctt_deploy'); hook.
		add_action('ctt_deploy', array('cttPlugin', 'deploy'));
		
		// Initialize shortcode
		cttPluginShortcode::init();
		
		// Initialize plugin updater
		// problem to fix:  Doesn't run on plugin init, only after view changes.
		cttPluginInstaller::init();

	}		

		
	/**
	 * Enqueues frontend scripts and styles.
	 * Should always be called on the wp_enqueue_scripts hook.
	 * @since 1.1.0
	 */
	static function enqueueFrontendScripts()
	{

wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css'); 

		wp_enqueue_script(
			'envent-tracker-crm-script',
			self::getPluginUrl() . '/js/min/all.frontend.min.js',
			array('jquery')
		);
		wp_enqueue_script(
			'ajax-handler',
			self::getPluginUrl() . '/js/min/all.ajax.min.js',
			array('jquery')
		);
		wp_localize_script(
			'ajax-handler',
			'ajax_postHandler', 
			array('ajaxurl' => admin_url('admin-ajax.php'))
		);
		wp_enqueue_style('crm-plugin-bootstrap', self::getPluginUrl() . '/css/custom-css/mybs.css');
		wp_enqueue_style(
			'envent-tracker-crm-style',
			self::getPluginUrl() . '/css/all.frontend.css'//,
			//SlideshowPluginMain::$version
		);
		wp_enqueue_style( 'crm-plugin-respnosive', self::getPluginUrl() . '/css/custom-css/responsive.css');	
		wp_enqueue_style( 'crm-plugin-docs', self::getPluginUrl() . '/css/custom-css/docs.css');
		

	}

	/**
	 * Enqueues backend scripts and styles.
	 * Should always be called on the admin_enqueue_scrips hook.
	 * @since 1.0.0
	 */
	static function enqueueBackendScripts()
	{
		wp_enqueue_script(
			'envent-tracker-crm-backend-script',
			self::getPluginUrl() . '/js/min/all.backend.min.js',
			array( 
				'jquery' 
			)//,
			//cttPluginMain::$version
		);
		wp_enqueue_style(
			'envent-tracker-crm-backend-style',
			self::getPluginUrl() . '/css/all.backend.css'//,
			//cttPluginMain::$version
		);
	}


	/**
	 * Returns url to the base directory of this plugin.
	 * @since 1.0.0
	 * @return string pluginUrl
	 */
	static function getPluginUrl()
	{
		return plugins_url('', __FILE__);
	}

	/**
	 * Returns path to the base directory of this plugin
	 * @since 1.0.0
	 * @return string pluginPath
	 */
	static function getPluginPath()
	{
		return dirname(__FILE__);
	}

	/**
	 * Outputs the passed view. It's good practice to pass an object like stdClass to the $data variable, as it can
	 * be easily checked for validity in the view itself using "instanceof".
	 * @since 1.0.0
	 * @param string   $view
	 * @param stdClass $data (Optional, defaults to stdClass)
	 */
	static function outputView($view, $data = null)
	{
		if (!($data instanceof stdClass))
		{
			$data = new stdClass();
			//$data->security = true;
		}

		$file = self::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view;

		if (file_exists($file))
		{
			include $file;
		}
	}

	/**
	 * Uses self::outputView to render the passed view. Returns the rendered view instead of outputting it.
	 * @since 1.0.0
	 * @param string   $view
	 * @param stdClass $data (Optional, defaults to null)
	 * @return string
	 */
	static function getView($view, $data = null)
	{
		ob_start();
		self::outputView($view, $data);
		return ob_get_clean();
	}

	/**
	 * This function will load classes automatically on-call.
	 * @since 1.0.0
	 */
	static function autoInclude()
	{
		if (!function_exists('spl_autoload_register'))
		{
			return;
		}

		function cttPluginAutoLoader($name)
		{
			$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
			$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

			if (is_file($file))
			{
				require_once $file;
			}
		}

		spl_autoload_register('cttPluginAutoLoader');
	}

}





/**
 * Activate plugin
 */
cttPluginMain::bootStrap();


 
