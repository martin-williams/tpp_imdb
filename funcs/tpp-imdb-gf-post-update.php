<?php 

/// removes gravity forms post updates class and replaces
/// it with an extended class that will have some changes to it's operation


$path = plugin_dir_path(__FILE__);
$plug_url = $path . '../../gravity-forms-post-updates/gravityforms-update-post.php';
require_once $plug_url;



if(!class_exists('tppdb_gform_update_post')) :

//remove_action( 'init', array('gform_update_post', 'init'), 10 );
add_action( 'init', array('tppdb_gform_update_post', 'init'), 5);

class tppdb_gform_update_post extends gform_update_post {
	

	// function __construct() {
 //        parent::__construct();
 //    }

    /**
	 * Initialize the Class
	 *
	 * Add filters and actions and set up the options.
	 *
	 * @author  Jake Snyder
	 * @date	22/08/13
	 */
	public static function init()
	{
		self::setup();

		// actions
		add_action( 'admin_init',                  array(__CLASS__, 'admin_init') );

		// filters
		add_filter( 'shortcode_atts_gravityforms', array(__CLASS__, 'gf_shortcode_atts'), 10, 3 );
	}

	/**
	 * Populate Field Elements
	 *
	 * Populate specific form fields based on type.
	 *
	 * @author  Kevin Miller
	 * @author  Jake Snyder
	 * @param	array	$field
	 * @param	string	$field_type
	 * @param	mixed	$value
	 * @return	array	$field Modified $field array
	 */
	public static function populate_element( $field, $field_type, $value )
	{
		$value = maybe_unserialize($value);

		switch ( $field_type )
		{
			case 'post_category':

				$field['allowsPrepopulate'] = true;
				$field['inputName'] = $field_type;

				self::$settings['cat_value'] = $value;
				add_filter( 'gform_field_value_' . $field['inputName'], array(__CLASS__, 'return_category_field_value'), 10, 2 );
				#add_filter( 'gform_field_value_' . $field['inputName'], function($value) use($value) { return $value; } );
				break;

			case 'populateTaxonomy':

				$field['allowsPrepopulate'] = true;
				$field['inputName'] = $field['populateTaxonomy'];

				self::$settings['tax_value'][$field['inputName']] = $value;
				add_filter( 'gform_field_value_' . $field['inputName'], array(__CLASS__, 'return_taxonomy_field_value') , 10, 2 );
				#add_filter( 'gform_field_value_' . $field['inputName'], function($value) use($value) { return $value; } );
				break;

			case 'list':

				if ( is_array($value) )
				{

					 $value = unserialize($value[0]); 

					// $new_value = array();
					// foreach ( $value as $row )
					// {
					// 	$row = unserialize($row);

					// 	$row_array = explode('|', $row);
					// 	$new_value = array_merge($new_value, $row_array);
					// }
					$field['allowsPrepopulate'] = true;
					$field['inputName'] = $field['postCustomFieldName'];

					//$value = $new_value;
					add_filter( 'gform_field_value_' . $field['inputName'], function($value) use($value) { return $value; } );
				}
				break;


			#case 'select':
			case 'multiselect':
			case 'checkbox':
			#case 'radio':

				$value = (! is_array($value) ) ? array($value) : $value;

				if ( isset($field['choices']) )
				{
					foreach ( $field['choices'] as &$choice )
					{
						$choice['isSelected'] = ( in_array($choice['value'], $value) ) ? true : '';
					}
				}
				break;

			default:

				if ( is_array($value) )
				{
					$value = implode(', ', $value);
				}

				$field['defaultValue'] = $value;
				break;
		}
		return $field;
	}




}


endif;