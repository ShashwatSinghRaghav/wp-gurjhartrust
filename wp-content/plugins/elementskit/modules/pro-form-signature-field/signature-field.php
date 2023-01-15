<?php

namespace ElementsKit\Modules\Pro_Form_Signature_Field;

if (!\defined('ABSPATH')) exit;

use ElementorPro\Modules\Forms\Classes;
use Elementor\Controls_Manager;
use ElementorPro\Plugin;

/**
 * The Main base class for the signature field
 */
class Signature_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
{

    public function __construct()
    {
        add_action('elementor/widget/print_template', [$this, 'signature_field_print_template'], 10, 2);
        add_action('elementor/element/form/section_form_style/after_section_end', [$this, 'add_style_controls']);
        parent::__construct();
    }

    public function get_name()
    {
        return esc_html__('Signature', 'elementskit');
    }
    public function get_type()
    {
        return 'signature-field';
    }

    public function signature_field_print_template($template, $widget)
    {
        if ('form' === $widget->get_name()) {
            $template = \false;
        }
        return $template;
    }

    public function update_controls($widget)
    {
        $elementor = Plugin::elementor();
        $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
        if (is_wp_error($control_data)) {
            return;
        }
        $field_controls = [
            'signature_data_format' => [
                'name' => 'signature_data_format',
                'label' => esc_html__('Signature Format', 'elementskit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
					'image/png'  => esc_html__( 'PNG', 'elementskit' ),
					'image/jpeg' => esc_html__( 'JPEG', 'elementskit' )
				],
                'default' => 'image/png',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'condition' => [
                    'field_type' => $this->get_type()
                ]
            ],
            'signature_save_as_file' => [
                'name' => 'signature_save_as_file',
                'label' => esc_html__('Save as file', 'elementskit'),
                'default' => 'yes',
                'type' => Controls_Manager::SWITCHER,
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
                'condition' => [
                    'field_type' => $this->get_type()
                ]
            ]
        ];
        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }

    public function add_style_controls($widget)
    {
        $widget->start_controls_section(
            'elementskit_signature_field_styles', 
            [
                'label' => esc_html__('Signature Field', 'elementskit'), 
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $widget->add_control(
            'signature_field_background_color', 
            [
                'label' => esc_html__('Field Background Color', 'elementskit'), 
                'type' => Controls_Manager::COLOR, 
                'default' => '#ffffff', 
            ]
        );
        $widget->add_control(
            'signature_field_pen_color', 
            [
                'label' => esc_html__('Pen Color', 'elementskit'), 
                'type' => Controls_Manager::COLOR, 
                'default' => '#000000',
            ]
        ); 
        $widget->end_controls_section();
    }

    public function render($field, $field_index, $form)
    {
        $settings = $form->get_settings_for_display();

        $form->add_render_attribute('input' . $field_index, 'class', 'elementskit-signature-input-field');
        $form->add_render_attribute('input' . $field_index, 'type', 'hidden', \true);
        $form->add_render_attribute('wrapper' . $field_index, 'signature-field-id', $field['custom_id']);
        $form->add_render_attribute('wrapper' . $field_index, 'class', 'elementskit-signature-field');

        $form->add_render_attribute('signature_canvas' . $field_index, 'height', '200');
        $form->add_render_attribute('signature_canvas' . $field_index, 'data-background-color', $settings['signature_field_background_color']);
        $form->add_render_attribute('signature_canvas' . $field_index, 'data-pen-color', $settings['signature_field_pen_color']);

        $form->add_render_attribute('signature_canvas' . $field_index, 'data-format', $field['signature_data_format']);
?>
        <div <?php $form->print_render_attribute_string('wrapper' . $field_index); ?>>
            <div class="elementskit-canvas-wrapper">
                <input <?php $form->print_render_attribute_string('input' . $field_index); ?>>
                <canvas class="elementor-field-textual elementor-field signature-pad" <?php $form->print_render_attribute_string('signature_canvas' . $field_index); ?> ></canvas>
                <button class="elementskit-clear-signature clear"><i class="icon icon-refresh"></i></button>
            </div>
        </div>
<?php
    }

    // Validating Signature Field
    public function validation($field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler)
    {
        $id = $field['id'];
        if ($field['required'] && $field['raw_value'] === '') {
            $ajax_handler->add_error($id, esc_html__('This signature field is required.', 'elementskit'));
        }
    }

    // Sanitizing Signature Field
    public function sanitize_field($value, $field)
    {
        if (preg_match('&^data:image/(jpeg|png);base64,[\\w\\d/+]+=*$&', $value, $matches)) {
            return $value;
        }
        return '';
    }

    public function process_field($field, Classes\Form_Record $record, Classes\Ajax_Handler $ajax_handler)
    {
        $value = $field['value'];
        $id = $field['id'];

        // Get all the settings for signature field
        $field_settings = $this->signature_field_settings($record, $id);

        // procceed next if only the file need to save as file settings enabled.
        if($field_settings['signature_save_as_file'] !== 'yes'){
            return;
        }

        // Save the data as file
        preg_match('&^data:image/(jpeg|png);base64,([\\w\\d/+]+=*)$&', $value, $matches);
        $extension = $matches[1];
        $encoded_image = $matches[2];

        $decoded_image = base64_decode($encoded_image);
        $dir_name = $field_settings['_id'];

        if (!preg_match('/[\\w\\d_]+/', $dir_name)) {
            $ajax_handler->add_admin_error_message(esc_html__('Invalid field ID', 'elementskit'));
            return;
        }

        $dir_abs_path = trailingslashit(wp_upload_dir()['basedir']) . 'elementskit/signatures/' . $dir_name;
        wp_mkdir_p($dir_abs_path);

        // Code from Elementor Upload field:
        $url = $this->upload_signature($extension, $dir_abs_path, $decoded_image, $dir_name, $ajax_handler);

        $record->update_field($field['id'], 'value', $url);

    }


    private function signature_field_settings($record, $id){
        $field_settings = $record->get_form_settings('form_fields');
        $field_settings = array_filter($field_settings, function ($field) use($id) {
            return $field['custom_id'] === $id;
        });
        $field_settings = array_values($field_settings)[0];

        return $field_settings;
    }

    private function upload_signature($extension, $directory_abs_path, $decoded_image, $dir_name, $ajax_handler){

        $filename = uniqid() . '.' . $extension;
        $filename = wp_unique_filename($directory_abs_path, $filename);
        $new_file = trailingslashit($directory_abs_path) . $filename;

        if (is_dir($directory_abs_path) && is_writable($directory_abs_path)) {
            $response = file_put_contents($new_file, $decoded_image);
            if ($response) {
                // File permission
                $perms = 0644;
                @chmod($new_file, $perms);
                return wp_upload_dir()['baseurl'] . '/elementskit/signatures/' . trailingslashit($dir_name) . $filename;
            } else {
                $ajax_handler->add_error_message(esc_html__('Failed to save signature.', 'elementskit'));
            }
        } else {
            $ajax_handler->add_admin_error_message(esc_html__('Signature directory is not exists or not writable.', 'elementskit'));
        }


    }
}