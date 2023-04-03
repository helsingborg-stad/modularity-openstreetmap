<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_64219abb0caec',
    'title' => __('OpenStreetMap', 'modularity-open-street-map'),
    'fields' => array(
        0 => array(
            'key' => 'field_642a8818a908c',
            'label' => __('Post type', 'modularity-open-street-map'),
            'name' => 'mod_osm_post_type',
            'type' => 'posttype_select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'placeholder' => '',
            'disabled' => 0,
            'readonly' => 0,
        ),
        1 => array(
            'key' => 'field_6422b15cf1b12',
            'label' => __('Select place categories to show', 'modularity-open-street-map'),
            'name' => 'mod_osm_terms_to_show',
            'type' => 'select',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'none' => __('No post found', 'modularity-open-street-map'),
            ),
            'default_value' => array(
            ),
            'return_format' => 'value',
            'multiple' => 1,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
        ),
        2 => array(
            'key' => 'field_6424301cc4fd8',
            'label' => __('Full width', 'modularity-open-street-map'),
            'name' => 'mod_osm_full_width',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        3 => array(
            'key' => 'field_642434d17fdaa',
            'label' => __('Post columns', 'modularity-open-street-map'),
            'name' => 'mod_osm_post_columns',
            'type' => 'select',
            'instructions' => __('This does not affect inner blocks', 'modularity-open-street-map'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'grid-md-12' => __('1', 'modularity-open-street-map'),
                'grid-md-6' => __('2', 'modularity-open-street-map'),
                'grid-md-4' => __('3', 'modularity-open-street-map'),
            ),
            'default_value' => __('grid-md-12', 'modularity-open-street-map'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
        ),
        4 => array(
            'key' => 'field_6426a19f8c575',
            'label' => __('Latitude start', 'modularity-open-street-map'),
            'name' => 'latitude_start',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'min' => '',
            'max' => '',
            'placeholder' => '',
            'step' => '',
            'prepend' => '',
            'append' => '',
        ),
        5 => array(
            'key' => 'field_6426a1dd8c576',
            'label' => __('Longitude start', 'modularity-open-street-map'),
            'name' => 'longitude_start',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'min' => '',
            'max' => '',
            'placeholder' => '',
            'step' => '',
            'prepend' => '',
            'append' => '',
        ),
        6 => array(
            'key' => 'field_6426a973f0745',
            'label' => __('Start zoom value', 'modularity-open-street-map'),
            'name' => 'start_zoom_value',
            'type' => 'number',
            'instructions' => __('A value between 5-20 that sets the zoom value. Default value is 14.', 'modularity-open-street-map'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 14,
            'min' => 5,
            'max' => 20,
            'placeholder' => '',
            'step' => '',
            'prepend' => '',
            'append' => '',
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-open-street-map',
            ),
        ),
        1 => array(
            0 => array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/open-street-map',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'left',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 1,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}