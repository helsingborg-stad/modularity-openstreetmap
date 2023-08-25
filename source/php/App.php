<?php

namespace ModularityOpenStreetMap;

use ModularityOpenStreetMap\Helper\Taxonomies as TaxonomiesHelper;
use Municipio\Customizer as Customizer;
use Kirki as Kirki;

class App
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontend'));
        add_action('plugins_loaded', array($this, 'registerModule'));
        add_action('municipio_customizer_section_registered', array($this, 'addKirkiPanel'), 11);

        add_filter('acf/load_field/name=mod_osm_post_type', array($this, 'postTypes'));
        add_filter('acf/prepare_field/name=mod_osm_terms_to_show', array($this, 'termsToShow'));
        add_filter( 'acf/prepare_field/name=mod_osm_full_width', array($this, 'handleFullWidthField'), 10, 2 );

        
        $this->cacheBust = new \ModularityOpenStreetMap\Helper\CacheBust();
    }

    public function postTypes($field) {
            $postTypes = get_post_types();
            $arr = [];
            foreach ($postTypes as $postType) {
                $purpose = \Municipio\Helper\ContentType::getContentType($postType);
                if (isset($purpose[0]->key) && $purpose[0]->key == 'place') {
                    $postTypeObject = get_post_type_object($postType);
                    $arr[$postTypeObject->name] = $postTypeObject->label;
                }
            }

        reset($arr);
        $first_key = key($arr);
        $field['default_value'] = $first_key;

        $field['choices'] = $arr;
        return $field;
    }

    public function termsToShow($field) {
        $postType = get_field('mod_osm_post_type');
        $arr = TaxonomiesHelper::getTerms($postType);

        if(empty($arr)) {
            $arr = ['none' => 'No post found'];
        }
        
        $field['choices'] = $arr;

        return $field;
    }

    public function handleFullWidthField( $field ) {
        if (get_post_type() == 'page') {
            return false;
        }
        return $field;
    }

        public function addKirkiPanel() {
        if (class_exists('Kirki')) {
            Kirki::add_section( 'openstreetmap', array(
                'title'          => esc_html__( 'OpenStreetMap', 'modularity-open-street-map' ),
                'panel'          => 'municipio_customizer_panel_design_module',
                'priority'       => 130,
                'capability'     => 'edit_theme_options',
            ) );
            Kirki::add_field(Customizer::KIRKI_CONFIG, [
                'type'        => 'select',
                'settings'    => 'osm_map_style',
                'label'       => esc_html__('Appearance', 'modularity-open-street-map'),
                'description' => esc_html__('Select if you want to use one of the predefined appearance, or customize freely.', 'modularity-open-street-map'),
                'section'     => 'openstreetmap',
                'default'     => 'default',
                'priority'    => 5,
                'choices'     => [
                    'default' => esc_html__('Default', 'modularity-open-street-map'),
                    'pale' => esc_html__('Pale', 'modularity-open-street-map'),
                    'dark' => esc_html__('Dark', 'modularity-open-street-map'),
                    'color' => esc_html__('Color', 'modularity-open-street-map'),
                ],
            ]);
        }
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueFrontend()
    {
        wp_register_style(
            'modularity-open-street-map-css',
            MODULARITYOPENSTREETMAP_URL . '/dist/' .
            $this->cacheBust->name('css/modularity-open-street-map.css')
        );

        wp_enqueue_style('modularity-open-street-map-css');

        wp_register_script(
            'modularity-open-street-map-js',
            MODULARITYOPENSTREETMAP_URL . '/dist/' .
            $this->cacheBust->name('js/modularity-open-street-map.js')
        );

        wp_enqueue_script('modularity-open-street-map-js');
    }

    /**
     * Register the module
     * @return void
     */
    public function registerModule()
    {
        if (function_exists('modularity_register_module')) {
            modularity_register_module(
                MODULARITYOPENSTREETMAP_PATH . 'source/php/Module/',
                'OpenStreetMap'
            );
        }
    }
}
