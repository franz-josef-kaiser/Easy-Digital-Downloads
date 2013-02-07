<?php

/**
 * Roles / caps
 *
 * @package     Easy Digital Downloads
 * @subpackage  Roles / Caps
 * @copyright   Copyright (c) 2012, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.4.4
*/


/**
 * This class handles the role creation and assignment of capabilities for those roles.
 *
 * These roles let us have Shop Accountants, Shop Workers, etc, each of whom can do certain things within the EDD store
 *
 * @since  1.4.4
 * @return void
 */

class EDD_Roles {

	function __construct() {
		$this->add_roles();
		$this->add_caps();
	}


	/**
	 * Add new shop roles with default WP caps
	 *
	 * @since  1.4.4
	 * @return void
	 */

	public function add_roles() {

		add_role( 'shop_manager', __( 'Shop Manager', 'edd' ), array(
		    'read'                   => true,
		    'read_private_pages'     => true,
		    'read_private_posts'     => true,
		    'edit_users'             => true,
		    'edit_posts'             => false,
		    'delete_posts'           => false,
		    'manage_categories'      => true,
		    'manage_links'           => true,
		    'moderate_comments'      => true,
		    'unfiltered_html'        => true,
		    'upload_files'           => true,
		   	'export'                 => true,
			'import'                 => true
		) );

		add_role( 'shop_accountant', __( 'Shop Accountant', 'edd' ), array(
		    'read'                   => true,
		    'edit_posts'             => false,
		    'delete_posts'           => false
		) );

		add_role( 'shop_worker', __( 'Shop Worker', 'edd' ), array(
		    'read'                   => true,
		    'edit_posts'             => false,
		    'delete_posts'           => false
		) );

		add_role( 'shop_vendor', __( 'Shop Vendor', 'edd' ), array(
		    'read'                   => true,
		    'edit_posts'             => false,
		    'delete_posts'           => false
		) );

	}

	/**
	 * Add new shop-specific capabilities
	 *
	 * @since  1.4.4
	 * @return void
	 */

	public function add_caps() {
		global $wp_roles;

		if ( class_exists('WP_Roles') )
			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();

		if ( is_object( $wp_roles ) ) {

			$wp_roles->add_cap( 'shop_manager', 'view_shop_reports' );
			$wp_roles->add_cap( 'shop_manager', 'export_shop_reports' );
			$wp_roles->add_cap( 'shop_manager', 'manage_shop_discounts' );
			$wp_roles->add_cap( 'shop_manager', 'manage_shop_settings' );

			$wp_roles->add_cap( 'administrator', 'view_shop_reports' );
			$wp_roles->add_cap( 'administrator', 'export_shop_reports' );
			$wp_roles->add_cap( 'administrator', 'manage_shop_discounts' );
			$wp_roles->add_cap( 'administrator', 'manage_shop_settings' );

			// Add the main post type capabilities
			$capabilities = $this->get_core_caps();
			foreach( $capabilities as $cap_group ) {
				foreach( $cap_group as $cap ) {
					$wp_roles->add_cap( 'shop_manager', $cap );
					$wp_roles->add_cap( 'administrator', $cap );
					$wp_roles->add_cap( 'shop_worker', $cap );
				}
			}

			$wp_roles->add_cap( 'shop_accountant', 'edit_products' );
			$wp_roles->add_cap( 'shop_accountant', 'read_private_prodcuts' );
			$wp_roles->add_cap( 'shop_accountant', 'view_shop_reports' );
			$wp_roles->add_cap( 'shop_accountant', 'export_shop_reports' );

			$wp_roles->add_cap( 'shop_vendor', 'read' );
			$wp_roles->add_cap( 'shop_vendor', 'read_product' );
			$wp_roles->add_cap( 'shop_vendor', 'edit_product' );
			$wp_roles->add_cap( 'shop_vendor', 'edit_products' );
			$wp_roles->add_cap( 'shop_vendor', 'delete_product' );
			$wp_roles->add_cap( 'shop_vendor', 'delete_products' );
			$wp_roles->add_cap( 'shop_vendor', 'publish_products' );
			$wp_roles->add_cap( 'shop_vendor', 'edit_published_products' );
			$wp_roles->add_cap( 'shop_vendor', 'upload_files' );

		}

	}


	/**
	 * Gets the core post type capabilties
	 *
	 * @since  1.4.4
	 * @return array
	 */

	public function get_core_caps() {

		$capabilities = array();

		$capability_types = array( 'product', 'shop_payment', 'shop_discount' );

		foreach( $capability_types as $capability_type ) {

			$capabilities[ $capability_type ] = array(

				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms"
			);
		}

		return $capabilities;
	}

}