<?php

/**
 * Fired during plugin activation
 *
 * @link       https://twitter.com/tassawer_
 * @since      1.0.0
 *
 * @package    Knife_Number
 * @subpackage Knife_Number/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Knife_Number
 * @subpackage Knife_Number/includes
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Knife_Number_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            global $wpdb;
            $prefix = $wpdb->get_blog_prefix();
            
            $creation_query =
            'CREATE TABLE IF NOT EXISTS ' . $prefix . 'knife_number (
                    `knife_id` int(20) NOT NULL AUTO_INCREMENT,
                    `knife_number` int(10) NOT NULL DEFAULT 0,
                    `knife_description` text,
                    PRIMARY KEY (`knife_id`)
                    );'; 
            
            $tble_creation = $wpdb->query( $creation_query );
	}

}
