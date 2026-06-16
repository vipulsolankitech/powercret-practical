<?php 
/**
 * Plugin Name: Blog Summery
 * Plugin URI:  http://localhost/   
 * Description: plugin to Summarise blog using AI   
 * Version:     1.0.0
 * Author:      vipul   
 * Author URI:  https://vipulsolankitech.com/
 * License:     GPL2
 */

if (!defined('ABSPATH')) {
    die("You Can't access this path directoly"); // Exit if accessed directly
}

function summery_assets() {

    // Style
    wp_enqueue_style(
        'summery-style',
        plugin_dir_url(__FILE__) . 'assets/css/style.css',
        array(),
        '1.0.0'
    );

    // Script
    wp_enqueue_script(
        'summery-script',
        plugin_dir_url(__FILE__) . 'assets/js/script.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_localize_script(
        'summery-script',
        'blogsummery',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('summery_nonce')
        )
    );
}

add_action('wp_enqueue_scripts', 'summery_assets');


function blog_button_after_content($content) {
    
    if (is_singular('post')) {

        $post_id = get_the_ID();

        $content .= '<button id="post_summery_btn" class="btn btn-success" data-id="' . esc_attr($post_id) . '">
                        summarise blog
                    </button>';

        $content .= '<div class="summery_loader" style="display:none;">Loading.....</div><div id="post_summery_result" style="display:none"></div>';
    }
    return $content;
}
add_filter('the_content', 'blog_button_after_content');

include __DIR__ . '/inc/get_summery_result.php';

?>