<?php
/**
 * Register the meta box that holds the custom fields
 * (client_name, company, rating) on the Testimonial edit screen.
 */


if (!defined('ABSPATH')) {
    die("You Can't access this path directoly"); // Exit if accessed directly
}

function myplugin_add_testimonial_meta_box() {
    add_meta_box(
        'testimonial_details',          // unique id
        'Testimonial Details',          // box title
        'myplugin_render_testimonial_meta_box', // callback
        'testimonial',                  // post type
        'normal',                       // context
        'high'                          // priority
    );
}
add_action('add_meta_boxes', 'myplugin_add_testimonial_meta_box');

/**
 * Output the HTML of the meta box fields.
 */
function myplugin_render_testimonial_meta_box($post) {

    // Security nonce.
    wp_nonce_field('myplugin_save_testimonial', 'myplugin_testimonial_nonce');

    // Read currently stored values.
    $client_name = get_post_meta($post->ID, '_testimonial_client_name', true);
    $company     = get_post_meta($post->ID, '_testimonial_company', true);
    $rating      = get_post_meta($post->ID, '_testimonial_rating', true);
    ?>
    <p>
        <label for="testimonial_client_name"><strong>Client Name</strong></label><br>
        <input type="text" id="testimonial_client_name" name="testimonial_client_name"
               value="<?php echo esc_attr($client_name); ?>" class="widefat">
    </p>
    <p>
        <label for="testimonial_company"><strong>Company</strong></label><br>
        <input type="text" id="testimonial_company" name="testimonial_company"
               value="<?php echo esc_attr($company); ?>" class="widefat">
    </p>
    <p>
        <label for="testimonial_rating"><strong>Rating (1 to 5)</strong></label><br>
        <select id="testimonial_rating" name="testimonial_rating">
            <option value="">— Select —</option>
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                    <?php echo $i; ?>
                </option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

/**
 * Save the custom field values when the post is saved.
 */
function myplugin_save_testimonial_meta($post_id) {

    // Only act on our post type (case-insensitive, just to be safe).
    if (strtolower(get_post_type($post_id)) !== 'testimonial') {
        return;
    }

    // Verify nonce.
    if (!isset($_POST['myplugin_testimonial_nonce']) ||
        !wp_verify_nonce($_POST['myplugin_testimonial_nonce'], 'myplugin_save_testimonial')) {
        return;
    }

    // Skip autosaves and revisions.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Client name.
    if (isset($_POST['testimonial_client_name'])) {
        update_post_meta($post_id, '_testimonial_client_name',
            sanitize_text_field($_POST['testimonial_client_name']));
    }

    // Company.
    if (isset($_POST['testimonial_company'])) {
        update_post_meta($post_id, '_testimonial_company',
            sanitize_text_field($_POST['testimonial_company']));
    }

    // Rating — clamp to the 1..5 range.
    if (isset($_POST['testimonial_rating']) && $_POST['testimonial_rating'] !== '') {
        $rating = absint($_POST['testimonial_rating']);
        if ($rating < 1) {
            $rating = 1;
        } elseif ($rating > 5) {
            $rating = 5;
        }
        update_post_meta($post_id, '_testimonial_rating', $rating);
    } else {
        delete_post_meta($post_id, '_testimonial_rating');
    }
}
add_action('save_post', 'myplugin_save_testimonial_meta');

/**
 * Helper: build the star-icon markup for a given rating value.
 */
function myplugin_get_rating_stars($rating) {
    $rating = absint($rating);
    $out    = '<span class="testimonial-stars" style="color:#f5a623;letter-spacing:2px;">';
    for ($i = 1; $i <= 5; $i++) {
        // Filled star up to the rating, empty star after.
        $out .= ($i <= $rating) ? '&#9733;' : '<span style="color:#ccc;">&#9734;</span>';
    }
    $out .= '</span>';
    return $out;
}

/**
 * Append the custom fields (with star rating) to the testimonial content
 * on the front end.
 */
function myplugin_render_testimonial_content($content) {
    if (is_singular('testimonial') && in_the_loop() && is_main_query()) {
        $post_id     = get_the_ID();
        $client_name = get_post_meta($post_id, '_testimonial_client_name', true);
        $company     = get_post_meta($post_id, '_testimonial_company', true);
        $rating      = get_post_meta($post_id, '_testimonial_rating', true);

        $extra = '<div class="testimonial-meta">';
        if ($client_name) {
            $extra .= '<p class="testimonial-client"><strong>' . esc_html($client_name) . '</strong>';
            if ($company) {
                $extra .= ' — ' . esc_html($company);
            }
            $extra .= '</p>';
        } elseif ($company) {
            $extra .= '<p class="testimonial-company">' . esc_html($company) . '</p>';
        }
        if ($rating) {
            $extra .= '<p class="testimonial-rating">' . myplugin_get_rating_stars($rating) . '</p>';
        }
        $extra .= '</div>';

        $content .= $extra;
    }
    return $content;
}
add_filter('the_content', 'myplugin_render_testimonial_content');

/**
 * Add custom columns to the Testimonial admin list table.
 */
function myplugin_testimonial_columns($columns) {
    // Insert our columns before the Date column.
    $new = array();
    foreach ($columns as $key => $label) {
        if ($key === 'date') {
            $new['client_name'] = 'Client Name';
            $new['company']     = 'Company';
            $new['rating']      = 'Rating';
        }
        $new[$key] = $label;
    }
    // Fallback in case there is no date column.
    if (!isset($new['rating'])) {
        $new['client_name'] = 'Client Name';
        $new['company']     = 'Company';
        $new['rating']      = 'Rating';
    }
    return $new;
}
add_filter('manage_testimonial_posts_columns', 'myplugin_testimonial_columns');

/**
 * Fill the custom columns with their values.
 */
function myplugin_testimonial_column_content($column, $post_id) {
    switch ($column) {
        case 'client_name':
            echo esc_html(get_post_meta($post_id, '_testimonial_client_name', true));
            break;
        case 'company':
            echo esc_html(get_post_meta($post_id, '_testimonial_company', true));
            break;
        case 'rating':
            $rating = get_post_meta($post_id, '_testimonial_rating', true);
            echo $rating ? myplugin_get_rating_stars($rating) : '—';
            break;
    }
}
add_action('manage_testimonial_posts_custom_column', 'myplugin_testimonial_column_content', 10, 2);

/**
 * Make the custom columns sortable.
 */
function myplugin_testimonial_sortable_columns($columns) {
    $columns['client_name'] = 'client_name';
    $columns['company']     = 'company';
    $columns['rating']      = 'rating';
    return $columns;
}
add_filter('manage_edit-testimonial_sortable_columns', 'myplugin_testimonial_sortable_columns');

/**
 * Teach the query how to sort by our meta-based columns.
 */
function myplugin_testimonial_sort_query($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('client_name' === $orderby) {
        $query->set('meta_key', '_testimonial_client_name');
        $query->set('orderby', 'meta_value');
    } elseif ('company' === $orderby) {
        $query->set('meta_key', '_testimonial_company');
        $query->set('orderby', 'meta_value');
    } elseif ('rating' === $orderby) {
        $query->set('meta_key', '_testimonial_rating');
        $query->set('orderby', 'meta_value_num'); // numeric sort for the rating
    }
}
add_action('pre_get_posts', 'myplugin_testimonial_sort_query');

/**
 * Register the front-end stylesheet (enqueued on demand by the shortcode).
 */
function myplugin_register_testimonials_assets() {
    wp_register_style(
        'myplugin-testimonials',
        plugins_url('assets/css/testimonials.css', dirname(__DIR__) . '/Testimonial.php'),
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'myplugin_register_testimonials_assets');

/**
 * Shortcode: [testimonials limit="3"]
 *
 * Lists testimonials showing author/client name, company, star rating
 * and the testimonial content.
 */
function myplugin_testimonials_shortcode($atts) {

    // Load the stylesheet only when the shortcode is actually used.
    wp_enqueue_style('myplugin-testimonials');

    $atts = shortcode_atts(
        array(
            'limit' => 3, // how many testimonials to show
        ),
        $atts,
        'testimonials'
    );

    $query = new WP_Query(array(
        'post_type'      => 'testimonial',
        'post_status'    => 'publish',
        'posts_per_page' => intval($atts['limit']),
        'no_found_rows'  => true,
    ));

    if (!$query->have_posts()) {
        return '<p class="testimonials-empty">No testimonials found.</p>';
    }

    ob_start();
    echo '<div class="testimonials-list">';

    while ($query->have_posts()) {
        $query->the_post();

        $post_id     = get_the_ID();
        $client_name = get_post_meta($post_id, '_testimonial_client_name', true);
        $company     = get_post_meta($post_id, '_testimonial_company', true);
        $rating      = get_post_meta($post_id, '_testimonial_rating', true);

        // Fall back to the post title if no client name was entered.
        if (!$client_name) {
            $client_name = get_the_title();
        }
        ?>
        <div class="testimonial-item">
            <?php if ($rating) : ?>
                <p class="testimonial-rating"><?php echo myplugin_get_rating_stars($rating); ?></p>
            <?php endif; ?>

            <div class="testimonial-content"><?php the_content(); ?></div>

            <p class="testimonial-author">
                <strong><?php echo esc_html($client_name); ?></strong><?php
                if ($company) {
                    echo ' <span class="testimonial-company">— ' . esc_html($company) . '</span>';
                }
                ?>
            </p>
        </div>
        <?php
    }

    echo '</div>';
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('testimonials', 'myplugin_testimonials_shortcode');


