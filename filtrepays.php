<?php
/*
 * Plugin Name: Destinations par pays
 * Description: Un plugin pour filtrer les destinations par pays via une requête REST API.
 * Version: 1.0
 * Author: Philippe Cossette
 */

// Enqueue the necessary assets (styles and scripts)
function destinations_par_pays_enqueue_assets() {
    $version_css = filemtime(plugin_dir_path(__FILE__) . 'css/style.css');
    $version_js = filemtime(plugin_dir_path(__FILE__) . 'js/main.js');

    wp_enqueue_style(
        'destinations-pays-style',
        plugin_dir_url(__FILE__) . 'css/style.css',
        array(),
        $version_css
    );

    wp_enqueue_script(
        'destinations-pays-script',
        plugin_dir_url(__FILE__) . 'js/main.js',
        array('jquery'),
        $version_js,
        true
    );

    // Pass the REST URL to JavaScript
    wp_localize_script('destinations-pays-script', 'dp_ajax_obj', array(
        'rest_url' => esc_url(rest_url('/wp/v2/posts')),
        'nonce'    => wp_create_nonce('wp_rest')
    ));
}
add_action('wp_enqueue_scripts', 'destinations_par_pays_enqueue_assets');

