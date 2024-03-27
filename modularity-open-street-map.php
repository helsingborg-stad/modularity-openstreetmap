<?php

/**
 * Plugin Name:       Modularity Open Street Map
 * Plugin URI:        https://github.com/NiclasNorin/modularity-open-street-map
 * Description:       A map based of OSM.
 * Version: 2.0.7
 * Author:            Niclas Norin
 * Author URI:        https://github.com/NiclasNorin
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       modularity-open-street-map
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('MODULARITYOPENSTREETMAP_PATH', plugin_dir_path(__FILE__));
define('MODULARITYOPENSTREETMAP_URL', plugins_url('', __FILE__));
define('MODULARITYOPENSTREETMAP_TEMPLATE_PATH', MODULARITYOPENSTREETMAP_PATH . 'templates/');
define('MODULARITYOPENSTREETMAP_VIEW_PATH', MODULARITYOPENSTREETMAP_PATH . 'views/');
define('MODULARITYOPENSTREETMAP_MODULE_VIEW_PATH', MODULARITYOPENSTREETMAP_PATH . 'source/php/Module/views');

require_once MODULARITYOPENSTREETMAP_PATH . 'Public.php';

// Register the autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

add_filter('/Modularity/externalViewPath', function ($arr) {
    $arr['mod-open-street-map'] = MODULARITYOPENSTREETMAP_MODULE_VIEW_PATH;
    return $arr;
}, 10, 3);

// Acf auto import and export
add_action('acf/init', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity-open-street-map');
    $acfExportManager->setExportFolder(MODULARITYOPENSTREETMAP_PATH . 'source/php/AcfFields/');
    $acfExportManager->autoExport(array(
        'modularity-open-street-map-settings' => 'group_64219abb0caec' //Update with acf id here, settings view
    ));
    $acfExportManager->import();
});

// Start application
new ModularityOpenStreetMap\App();

load_plugin_textdomain('modularity-open-street-map', false, plugin_basename(dirname(__FILE__)) . '/languages');
