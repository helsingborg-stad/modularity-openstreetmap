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
            'aria-label' => '',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'place' => __('Places', 'modularity-open-street-map'),
                'news' => __('News', 'modularity-open-street-map'),
            ),
            'default_value' => __('place', 'modularity-open-street-map'),
            'return_format' => 'value',
            'multiple' => 0,
            'allow_null' => 0,
            'ui' => 0,
            'ajax' => 0,
            'placeholder' => '',
            'allow_custom' => 0,
            'search_placeholder' => '',
        ),
        1 => array(
            'key' => 'field_6422b15cf1b12',
            'label' => __('Select place categories to show', 'modularity-open-street-map'),
            'name' => 'mod_osm_terms_to_show',
            'aria-label' => '',
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
            'allow_custom' => 0,
            'placeholder' => '',
            'allow_null' => 0,
            'ui' => 1,
            'ajax' => 0,
            'search_placeholder' => '',
        ),
        2 => array(
            'key' => 'field_644b51b53a332',
            'label' => __('Starting point and zoom level', 'modularity-open-street-map'),
            'name' => 'map_start_values',
            'aria-label' => '',
            'type' => 'google_map',
            'instructions' => __('Click on the map to place a marker for the default center of the map. Use the plus- and minus-signs (+/-) to set the default zoom level.', 'modularity-open-street-map'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'center_lat' => '56.048767661222115',
            'center_lng' => '12.703459264197518',
            'zoom' => '',
            'height' => '',
        ),
        3 => array(
            'key' => 'field_668d19163553d',
            'label' => __('Filter', 'modularity-open-street-map'),
            'name' => 'mod_osm_filters',
            'aria-label' => '',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'acfe_repeater_stylised_button' => 0,
            'layout' => 'table',
            'pagination' => 0,
            'min' => 0,
            'max' => 0,
            'collapsed' => '',
            'button_label' => __('Add Row', 'modularity-open-street-map'),
            'rows_per_page' => 20,
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_668d1cb10d852',
                    'label' => __('Text', 'modularity-open-street-map'),
                    'name' => 'mod_osm_filter_text',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'parent_repeater' => 'field_668d19163553d',
                ),
                1 => array(
                    'key' => 'field_668d1cc80d853',
                    'label' => __('select', 'modularity-open-street-map'),
                    'name' => 'mod_osm_filter_taxonomy',
                    'aria-label' => '',
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
                    ),
                    'default_value' => false,
                    'return_format' => 'value',
                    'multiple' => 0,
                    'allow_null' => 0,
                    'ui' => 0,
                    'ajax' => 0,
                    'placeholder' => '',
                    'allow_custom' => 0,
                    'search_placeholder' => '',
                    'parent_repeater' => 'field_668d19163553d',
                ),
            ),
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
    'instruction_placement' => 'above_field',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'acfe_display_title' => '',
    'acfe_autosync' => '',
    'acfe_form' => 0,
    'acfe_meta' => '',
    'acfe_note' => '',
));
}