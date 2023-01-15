<?php

namespace ElementsKit\Modules\Pro_Form_Signature_Field;

defined('ABSPATH') || exit;

class Init
{
	private $dir;
	private $url;

	public function __construct()
	{
		if(!class_exists('\ElementorPro\Core\App\App' ) ){
			return;
		}
		// get current directory path
		$this->dir = dirname(__FILE__) . '/';

		// get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/pro-form-signature-field/';

		// enqueue scripts
		add_action('elementor/frontend/before_enqueue_scripts', [$this, 'editor_scripts']);

		//include all necessary files
		$this->include_files();

		// calling the sticky controls
		new Signature_Field();
	}

	public function include_files()
	{
		include $this->dir . 'signature-field.php';
	}

	public function editor_scripts()
	{
		wp_enqueue_style('elementskit-signature-field-css', $this->url . 'assets/css/signature-field-styles.css', [], \ElementsKit::version());

		wp_enqueue_script('elementskit-signature-pad', $this->url . 'assets/js/signature_pad.umd.min.js', array('jquery'), \ElementsKit::version(), true);

		wp_enqueue_script('elementskit-signature-field-scripts', $this->url . 'assets/js/elementskit-signature-field-scripts.js', array('jquery', 'elementor-frontend'), \ElementsKit::version(), true);
	}
}
