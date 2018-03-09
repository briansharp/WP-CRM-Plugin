<?php
/**
 * The cttPluginSecurity class contains functions for sanitizing in- and output.
 *
 * @since 1.0.0
 * @author Brian Sharp
 */
class crmPluginSecurity
{
	/**
	 * @since 1.0.0
	 * @var array List of allowed element tags
	 */
	private static $allowedElements = array(
		'b'      	=> array('endTag' => true, 'attributes' => 'default'),
		'br'     	=> array('endTag' => false),
		'div'    	=> array('endTag' => true, 'attributes' => 'default'),
		'h1'     	=> array('endTag' => true, 'attributes' => 'default'),
		'h2'     	=> array('endTag' => true, 'attributes' => 'default'),
		'h3'     	=> array('endTag' => true, 'attributes' => 'default'),
		'h4'     	=> array('endTag' => true, 'attributes' => 'default'),
		'h5'     	=> array('endTag' => true, 'attributes' => 'default'),
		'h6'     	=> array('endTag' => true, 'attributes' => 'default'),
		'i'      	=> array('endTag' => true, 'attributes' => 'default'),
		'li'     	=> array('endTag' => true, 'attributes' => 'default'),
		'ol'     	=> array('endTag' => true, 'attributes' => 'default'),
		'p'      	=> array('endTag' => true, 'attributes' => 'default'),
		'span'   	=> array('endTag' => true, 'attributes' => 'default'),
		'strong' 	=> array('endTag' => true, 'attributes' => 'default'),
		'table'  	=> array('endTag' => true, 'attributes' => 'default'),
		'tbody'  	=> array('endTag' => true, 'attributes' => 'default'),
		'td'     	=> array('endTag' => true, 'attributes' => 'default'),
		'tfoot'  	=> array('endTag' => true, 'attributes' => 'default'),
		'th'     	=> array('endTag' => true, 'attributes' => 'default'),
		'thead'  	=> array('endTag' => true, 'attributes' => 'default'),
		'tr'     	=> array('endTag' => true, 'attributes' => 'default'),
		'ul'     	=> array('endTag' => true, 'attributes' => 'default'),
		'input'     => array('endTag' => true, 'attributes' => 'default'),
		'label'     => array('endTag' => true, 'attributes' => 'default'),
		'textarea'  => array('endTag' => true, 'attributes' => 'default')
	);

	/**
	 * @since 1.1.0
	 * Checks if the user has permission and regects if not
	 * @string permission as it is in database
	 */
	static function secure(permission){		
		global $wpdb; 
		$tbl_users = $wpdb->prefix . "issupportmembers";
		$row = $wpdb->get_row( "SELECT * FROM $tbl_users WHERE userid=" . get_current_user_id() . ";" );
		
		if (!$row->permission && !$row->superuser){
			$data = new stdClass();
			$viewName = "denied.php";		
			return cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $viewName, $data);
			wp_die();
		};
		else return false;
	}
	
	/**
	 * Similar to the htmlspecialchars($text) function, except this function
	 * allows the exceptions defined in this class.
	 *
	 * @since 1.0.0
	 */
	static function htmlspecialchars_allow_exceptions($text)
	{
		$text = htmlspecialchars(htmlspecialchars_decode($text));

		$allowedElements = self::$allowedElements;

		// Loop through allowed elements decoding their HTML special chars and allowed attributes.
		if (is_array($allowedElements) &&
			count($allowedElements) > 0)
		{
			foreach ($allowedElements as $element => $attributes)
			{
				$position = 0;

				while (($position = stripos($text, $element, $position)) !== false) // While element tags found
				{
					$openingTag        = '<';
					$encodedOpeningTag = htmlspecialchars($openingTag);

					if (substr($text, $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag)) == $encodedOpeningTag) // Check if an opening tag '<' can be found before the tag name
					{
						// Replace encoded opening tag
						$text      = substr_replace($text, '<', $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag));
						$position -= strlen($encodedOpeningTag) - strlen($openingTag);

						// Get the position of the first element closing tag
						$closingTag         = '>';
						$encodedClosingTag  = htmlspecialchars($closingTag);
						$closingTagPosition = stripos($text, $encodedClosingTag, $position);

						// Replace encoded closing tag
						if ($closingTagPosition !== false)
						{
							$text = substr_replace($text, '>', $closingTagPosition, strlen($encodedClosingTag));
						}

						$elementAttributes = null;

						if (isset($attributes['attributes']) && is_array($attributes['attributes']))
						{
							$elementAttributes = $attributes['attributes'];
						}
						elseif (isset($attributes['attributes']) && $attributes['attributes'] == 'default')
						{
							$elementAttributes = self::$defaultAllowedAttributes;
						}
						else
						{
							continue;
						}

						if (!is_array($elementAttributes))
						{
							continue;
						}

						$tagText = substr($text, $position, $closingTagPosition - $position);

						// Decode allowed attributes
						foreach ($elementAttributes as $attribute)
						{
							$attributeOpener = $attribute . '=' . htmlspecialchars('"');

							$attributePosition = 0;

							if (($attributePosition = stripos($tagText, $attributeOpener, $attributePosition)) !== false) // Attribute was found
							{
								$attributeClosingPosition = 0;

								if (($attributeClosingPosition = stripos($tagText, htmlspecialchars('"'), $attributePosition + strlen($attributeOpener))) === false) // If no closing position of attribute was found, skip.
								{
									continue;
								}

								// Open the attribute
								$tagText = str_ireplace($attributeOpener, $attribute . '="', $tagText);

								// Close the attribute
								$attributeClosingPosition -= strlen($attributeOpener) - strlen($attribute . '="');
								$tagText                   = substr_replace($tagText, '"', $attributeClosingPosition, strlen(htmlspecialchars('"')));
							}

						}

						// Put the attributes of the tag back in place
						$text = substr_replace($text, $tagText, $position, $closingTagPosition - $position);
					}

					$position++;
				}

				// Decode closing tags
				if (isset($attributes['endTag']) && $attributes['endTag'])
				{
					$text = str_ireplace(htmlspecialchars('</' . $element . '>'), '</' . $element . '>', $text);
				}
			}
		}

		return $text;
	}
}