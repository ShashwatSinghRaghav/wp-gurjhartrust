<?php
namespace Elementor;

class ElementsKit_Widget_Advanced_Slider_Handler extends \ElementsKit_Lite\Core\Handler_Widget {

	static function get_name() {
		return 'elementskit-advanced-slider';
	}

	static function get_title() {
		return esc_html__( 'Advanced Slider', 'elementskit' );
	}

	static function get_icon() {
		return 'ekit-widget-icon eicon-spacer';
	}

	static function get_categories() {
		return [ 'elementskit' ];
	}

	static function get_dir() {
		return \ElementsKit::widget_dir() . 'advanced-slider/';
	}

	static function get_url() {
		return \ElementsKit::widget_url() . 'advanced-slider/';
	}
}