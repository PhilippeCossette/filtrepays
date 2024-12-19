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

// Generate country buttons
function destinations_par_pays_generate_buttons() {
    $countries = [
        'France', 'États-Unis', 'Canada', 'Argentine', 'Chili', 'Belgique', 'Maroc', 
        'Mexique', 'Japon', 'Italie', 'Islande', 'Chine', 'Grèce', 'Suisse'
    ];

    $buttons = '<div class="destinations-pays-buttons">';
    foreach ($countries as $country) {
        $buttons .= '<button class="btn-pays" data-pays="' . esc_attr($country) . '">' . esc_html($country) . '</button>';
    }
    $buttons .= '</div>';
    $buttons .= '<div id="destinations-pays-results"></div>';
    
    return $buttons;
}
add_shortcode('destinations_par_pays', 'destinations_par_pays_generate_buttons');

// Register REST API route to get posts by country
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/posts', array(
        'methods' => 'GET',
        'callback' => 'destinations_par_pays_get_destinations',
        'permission_callback' => '__return_true'
    ));
});

// Callback to fetch destinations by country
function destinations_par_pays_get_destinations($request) {
    $country = $request->get_param('search');
    if (!$country) {
        return new WP_Error('invalid_country', 'Pays invalide.', array('status' => 400));
    }

    // WP_Query to fetch posts related to the country
    $args = array(
        'post_type'   => 'post',  // You can change this if you have a custom post type for destinations
        's'           => $country,  // Search by country name
        'post_status' => 'publish',
        'posts_per_page' => 30,
    );

    $posts = get_posts($args);
    $data = array();

    foreach ($posts as $post) {
        $data[] = array(
            'title' => $post->post_title,
            'link'  => get_permalink($post->ID),
        );
    }

    return rest_ensure_response($data);
}
?>
