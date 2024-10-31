<?php

namespace DevOwl\RealCategoryLibrary\Vendor;

// We have now ensured that we are running the minimum PHP version.
use DevOwl\RealCategoryLibrary\Vendor\DevOwl\Freemium\Autoloader;
use DevOwl\RealCategoryLibrary\Core;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// Check minimum WordPress version
global $wp_version;
if (\version_compare($wp_version, \RCL_MIN_WP, '>=')) {
    $load_core = \false;
    // Check minimum WordPress REST API
    if (\version_compare($wp_version, '4.7.0', '>=')) {
        $load_core = \true;
    } else {
        // Check WP REST API plugin is active
        require_once \ABSPATH . 'wp-admin/includes/plugin.php';
        $load_core = \is_plugin_active('rest-api/plugin.php');
    }
    // Load core
    if ($load_core) {
        // Composer autoload with prioritized PHP Scoper autoload
        $composer_autoload_path = \RCL_PATH . '/vendor/autoload.php';
        $composer_scoper_autoload_path = \RCL_PATH . '/vendor/scoper-autoload.php';
        if (\file_exists($composer_scoper_autoload_path)) {
            require_once $composer_scoper_autoload_path;
        } elseif (\file_exists($composer_autoload_path)) {
            require_once $composer_autoload_path;
        }
        // Load no-namespace API functions
        foreach (['general'] as $apiInclude) {
            require_once \RCL_INC . 'api/' . $apiInclude . '.php';
        }
        new Autoloader('RCL');
        Core::getInstance();
    } else {
        // WP REST API version not reached
        require_once \RCL_INC . 'base/others/fallback-rest-api.php';
    }
} else {
    // Min WP version not reached
    require_once \RCL_INC . 'base/others/fallback-wp-version.php';
}
