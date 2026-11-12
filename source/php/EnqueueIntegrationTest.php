<?php

use ModularityOpenStreetMap\App;
use PHPUnit\Framework\TestCase;
use WpService\Implementations\NativeWpService;
use WpUtilService\WpUtilService;

function plugins_url($path = '', $plugin = '')
{
    return 'https://example.test/wp-content/plugins/map';
}

function wp_cache_get($key, $group = '', $force = false, &$found = null)
{
    return [];
}

function wp_register_script($handle, $src, $deps = [], $ver = false, $args = [])
{
    $GLOBALS['enqueueCalls'][] = ['register_script', $handle, $src, $deps, $ver, $args];
    return true;
}

function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $args = [])
{
    $GLOBALS['enqueueCalls'][] = ['enqueue_script', $handle];
}

function wp_register_style($handle, $src, $deps = [], $ver = false, $media = 'all')
{
    $GLOBALS['enqueueCalls'][] = ['register_style', $handle, $src, $deps, $ver, $media];
    return true;
}

function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all')
{
    $GLOBALS['enqueueCalls'][] = ['enqueue_style', $handle];
}

function wp_localize_script($handle, $objectName, $l10n)
{
    $GLOBALS['enqueueCalls'][] = ['localize_script', $handle, $objectName, $l10n];
    return true;
}

final class EnqueueIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['enqueueCalls'] = [];
    }

    public function testFrontendAssetsUseNativeWpService(): void
    {
        $manager = (new WpUtilService(new NativeWpService()))->enqueue('/var/www/wp-content/plugins/map');
        $app = (new ReflectionClass(App::class))->newInstanceWithoutConstructor();
        (new ReflectionProperty(App::class, 'wpEnqueue'))->setValue($app, $manager);

        $app->enqueueFrontend();

        self::assertSame([
            ['register_script', 'js-modularity-open-street-map-frontendjs', 'https://example.test/wp-content/plugins/map/assets/dist/js/modularity-open-street-map-frontend.js', [], false, true],
            ['enqueue_script', 'js-modularity-open-street-map-frontendjs'],
            ['register_style', 'css-modularity-open-street-mapcss', 'https://example.test/wp-content/plugins/map/assets/dist/css/modularity-open-street-map.css', [], false, 'all'],
            ['enqueue_style', 'css-modularity-open-street-mapcss'],
        ], $GLOBALS['enqueueCalls']);
    }

    public function testBackendAssetAndLocalizationUseNativeWpService(): void
    {
        $manager = (new WpUtilService(new NativeWpService()))->enqueue('/var/www/wp-content/plugins/map');
        $data = ['taxonomies' => ['place' => 'Places'], 'selected' => [1]];

        $manager->add('js/modularity-open-street-map-backend.js', ['jquery', 'acf-input'])
            ->with()->translation('osm', $data);

        self::assertSame([
            ['register_script', 'js-modularity-open-street-map-backendjs', 'https://example.test/wp-content/plugins/map/assets/dist/js/modularity-open-street-map-backend.js', ['jquery', 'acf-input'], false, true],
            ['enqueue_script', 'js-modularity-open-street-map-backendjs'],
            ['localize_script', 'js-modularity-open-street-map-backendjs', 'osm', $data],
        ], $GLOBALS['enqueueCalls']);
    }
}
