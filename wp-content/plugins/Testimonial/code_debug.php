<?php
// below is the code for fix
if (!defined('ABSPATH')) {
    die("You Can't access this path directoly"); // Exit if accessed directly
}

// below is the function that i fixed for security purposes..
function show_user_note() {
    // I added the condition to execute the code if i will get the user id in argment other wise return from here
    if (!isset($_GET['user_id'])) {
        return;
    }

    global $wpdb;

    // I added Sanitize input for security purposes to prevent unusual inputs from user
    //$user_id = 1;
    $user_id = absint($_GET['user_id']);
    $note    = isset($_GET['note']) ? sanitize_text_field($_GET['note']) : '';

    // I added Prepared statement for query To prevents SQL injection 
    $result = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_value 
             FROM {$wpdb->usermeta}
             WHERE user_id = %d 
             AND meta_key = %s",
            $user_id,
            'admin_note'
        )
    );

    // i added esc statements for Output safely with out execute any unusual script for security purposes
    echo "<div>Note: " . esc_html($note) . "</div>";
    echo "<div>Saved: " . esc_html($result) . "</div>";
    print_r($result);
}

add_action( 'wp_ajax_show_note', 'show_user_note' );

?>