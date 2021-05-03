<?php

/**
 * Plugin Name: ACF Repeater Elementor Icon List
 * Description: Adds ACF repeater data source to existing Elementor Icon List widget
 * Version:     1.0.0
 * Author:      Allan Christian Carlos
 * Text Domain: acf-repeater-elementor-icon-list
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Widget_Base;


add_action('elementor/element/before_section_end', function ($element, $section_id) {
	if ('icon-list' === $element->get_name() && $section_id === "section_icon") {
		$element->add_control(
			'acfreil_data_source',
			[
				'label' => __('Data Source', 'acf-repeater-elementor-icon-list'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'acf_repeater' => __('ACF Repeater', 'acf-repeater-elementor-icon-list'),
					'static' => __('Static', 'acf-repeater-elementor-icon-list'),
				],
				'default' => 'static',
			]
		);
		$element->add_control(
			'acfreil_repeater',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Repeater', 'plugin-name'),
				'condition' => [
					'acfreil_data_source' => 'acf_repeater',
				],
			]
		);
		$element->add_control(
			'acfreil_repeater_field',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __('Repeater Field', 'plugin-name'),
				'condition' => [
					'acfreil_data_source' => 'acf_repeater',
				],
			]
		);

		$element->add_control(
			'acfreil_selected_icon',
			[
				'label' => __('Icon', 'acf-repeater-elementor-icon-list'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
				'condition' => [
					'acfreil_data_source' => 'acf_repeater',
				],
			]
		);
	}
}, 10, 2);



add_action('elementor/widget/before_render_content', function (Widget_Base $widget) {
	if ('icon-list' === $widget->get_name()) {
		$data_soure = $widget->get_settings("acfreil_data_source");

		if ($data_soure === "static") {
			return;
		}

		$repeater = $widget->get_settings("acfreil_repeater");
		$repeater_field = $widget->get_settings("acfreil_repeater_field");
		$selected_icon = $widget->get_settings("acfreil_selected_icon");

		if ($repeater_field && $repeater) {
			$new_icon_list = array();
			$rows = get_field($repeater);

			foreach ($rows as $key => $row) {
				array_push($new_icon_list, array(
					"text" => $row[$repeater_field],
					"_id" => $key,
					"selected_icon" => $selected_icon
				));
			}
			$widget->set_settings("icon_list", $new_icon_list);
		}
	}
}, 10, 2);
