<?php
/**
 * Class cttPluginShortcode provides the shortcode function, which is called
 * on use of shortcode anywhere in the posts and pages. Also provides the shortcode
 * inserter, so that it's made easier for non-programmers to insert the shortcode
 * into a post or page.
 *
 * @since 1.0.0
 * @author: Brian Sharp
 */
class cttPluginShortcode
{
	/** @var string $shortCode */
	public static $shortCode = 'ctt_deploy';

	/**
	 * Initializes the shortcode, registering it and hooking the shortcode
	 * inserter media buttons.
	 * @since 1.0.0
	 */
	static function init()
	{
		// Register shortcode
		add_shortcode(self::$shortCode, array(__CLASS__, 'cttDeploy'));
	}

	/**
	 * Function cttDeploy
	 * @since 1.0.0
	 */
	static function cttDeploy()
	{
		return  cttPlugin::prepare();
	}


	
}