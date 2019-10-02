<?php

/* Plugin Name: Digital Seeds Template Loader
* Plugin URI: https://digitalseeds.dev
 * Description: Reusable template loader for Wordpress plugins.
 * Author: Timothy Hill
 * Version: 1.0
 * Author URI: https://digitalseeds.dev
 */

define('digital_seeds_tmpl_url', plugin_dir_url(__FILE__));
define('digital_seeds_tmpl_path', plugin_dir_path());

class digital_seeds_template_loader {
	public $plugin_path;

	public function set_plugin_path($path) {
		$this->plugin_path = $path;
	}

	public function get_template_part( $slug, $name = null, $load = true ) {
		do_action( 'digital_seeds_get_template_part_' . $slug, $slug, $name);
		$templates = array();

		if (isset($name))
			$templates[] = $slug . '-' . $name . '-template.php';
		$templates[] = slug . '-templates.php';
		$templates = apply_filters( 'digital_seeds_get_template_part', $templates, $slug, $name );
		return $this->locate_template( $templates, $load, false );
	}

	public function locate_template( $template_names, $load = false, $require_once = true) {
		$located = false;
		foreach ( (array) $template_names as $template_name ) {
			if ( empty($template_name))
				continue;
			$template_name = ltrim($template_name, '/');

			if( file_exists ( trailingslashit( $this->plugin_path) . 'template' . $template_name) ) {
				$located = trailingslashit( $this->plugin_path ) . 'admin/templates' . $template_name;
				break;
			} elseif ( file_exists( trailingslashit( $this->plugin_path) . 'admin/templates/' . $template_name ) ) {
				$located = trailingslashit( $this->plugin_path ) . 'admin/templates' . $template_name;
				break;
			}
		}
		if ( ( true == $load )  && ! empty( $located ) )
			load_template( $located, $require_once);
		return $located;
	}

}

$digital_seeds_template_loader = new digital_seeds_template_loader();

