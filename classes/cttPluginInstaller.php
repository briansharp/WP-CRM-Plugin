<?php
/**
 * cttPluginInstaller takes care of setting up setting values and transferring to newer version without
 * losing any settings.
 *
 * @since 1.0.0
 * @author Brian Sharp
 */
class cttPluginInstaller
{
	/** @var string $versionKey Version option key */
	private static $versionKey = 'ctt-plugin-version';

	/**
	 * Determines whether or not to perform an update to the plugin.
	 * Checks are only performed when on admin pages as not to slow down the website.
	 *
	 * @since 1.1.0
	 */
	static function init()
	{			
		// Only check versions in admin
		if (!is_admin())
		{
			return;
		}

		// Get version saved in database
		$currentVersion = get_option(self::$versionKey, null);

		if ($currentVersion == null ||
			self::firstVersionGreaterThanSecond(cttPluginMain::$version, $currentVersion))
		{			
			self::update($currentVersion);
		}
	}

	/**
	 * Updates to correct version
	 *
	 * @since 1.1.0
	 * @param string $currentVersion
	 */
	private static function update($currentVersion)
	{
		self:: crm_addtables();		
		// Set new version
		update_option(self::$versionKey, cttPluginMain::$version);
	}
	
	
	/**
	 * Function creates database tables
	 *
	 * @since 1.1.0
	 */
	private static function crm_addtables() {
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'customerlocation';	
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			customerid mediumint(9) NOT NULL,
			customerlocation varchar(300) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );		
		
		$table_name = $wpdb->prefix . 'events';	
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			techid mediumint(9) NOT NULL,
			customerid mediumint(9) NOT NULL,
			locationid mediumint(9),
			isremote bit,
			supporttypeid mediumint(9) NOT NULL,
			ticketcreated datetime,
			timein datetime,
			timeout datetime,
			subject varchar(400) NOT NULL,
			details varchar(4000),
			requestedby varchar(200) NOT NULL,
			isbillable bit,
			createdby mediumint(9),
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );		
		
		$table_name = $wpdb->prefix . 'customers';	
		$charset_collate = $wpdb->get_charset_collate();		
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			customername varchar(200) NOT NULL,
			isactive bit DEFAULT 1,
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );					
		
		$table_name = $wpdb->prefix . 'supporttype';	
		$charset_collate = $wpdb->get_charset_collate();	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			supporttype varchar(200) NOT NULL,
			isactive bit DEFAULT 1,
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );					
		
		$table_name = $wpdb->prefix . 'issupportmembers';	
		$charset_collate = $wpdb->get_charset_collate();	
		$sql = "CREATE TABLE $table_name (
			userid mediumint(9) NOT NULL 
			isactive bit DEFAULT 1,
			superuser bit DEFAULT 0,
			editusers bit DEFAULT 0,
			editsupportcategores bit DEFAULT 0,
			editcustomers bit DEFAULT 0,
			editevents bit DEFAULT 0,
			viewreports bit DEFAULT 0,
			viewevents bit DEFAULT 0			
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );		
	}
	

	/**
	 * Checks if the version input first is greater than the version input second.
	 *
	 * Version numbers are noted as such: x.x.x
	 *
	 * @since 1.0.0
	 * @param String $firstVersion
	 * @param String $secondVersion
	 * @return boolean $firstGreaterThanSecond
	 */
	private static function firstVersionGreaterThanSecond($firstVersion, $secondVersion)
	{
		// Return false if $firstVersion is not set
		if (empty($firstVersion) || !is_string($firstVersion)){
			return false;
		}

		// Return true if $secondVersion is not set
		if (empty($secondVersion) || !is_string($secondVersion)){
			return true;
		}

		// Separate main, sub and bug-fix version number from one another.
		$firstVersion  = explode('.', $firstVersion);
		$secondVersion = explode('.', $secondVersion);

		// Compare version numbers per piece
		for ($i = 0; $i < count($firstVersion); $i++)
		{
			if (isset($firstVersion[$i], $secondVersion[$i]))
			{
				if ($firstVersion[$i] > $secondVersion[$i])
				{
					return true;
				}
				elseif ($firstVersion[$i] < $secondVersion[$i])
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

		// Return false by default
		return false;
	}
}