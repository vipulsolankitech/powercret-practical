<?php 
/*
/* this is the response file of blog summery...
*/

if (!defined('ABSPATH')) {
    die("You Can't access this path directoly"); // Exit if accessed directly
}


function get_blog_summery() {

    check_ajax_referer('summery_nonce', 'nonce');

    $post_id = absint($_POST['post_id']);

    $post = get_post($post_id);
    $api_key = defined('MY_PLUGIN_OPENAI_API_KEY') ? MY_PLUGIN_OPENAI_API_KEY : '';
    
    if (!$post) {
        wp_send_json_error([
            'message' => 'Invalid post'
        ]);
    }

    $summary = sprintf(
        'This is a test AI summary for "%s". The actual OpenAI integration has not been configured yet.',
        $post->post_title
    );

    wp_send_json_success([
        'summary' => $summary,
        'post_id' => $post_id
    ]);
}


/*
/* this is function if i have the api key then my approch as below to getting response from api and showing the summery of blog 
*/

// function get_blog_summery() {

//     check_ajax_referer('summery_nonce', 'nonce');

//     $post_id = absint($_POST['post_id']);

//     $post = get_post($post_id);
//	   $api_key = defined('MY_PLUGIN_OPENAI_API_KEY') ? MY_PLUGIN_OPENAI_API_KEY : '';
//     if (!$post) {
//         wp_send_json_error([
//             'message' => 'Invalid post'
//         ]);
//     }

//     $content = wp_strip_all_tags($post->post_content);

//     $response = wp_remote_post(
//         'https://api.openai.com/v1/chat/completions',
//         [
//             'headers' => [
//                 'Authorization' => 'Bearer '.$api_key,
//                 'Content-Type'  => 'application/json',
//             ],
//             'body' => wp_json_encode([
//                 'model' => 'gpt-4.1-mini',
//                 'messages' => [
//                     [
//                         'role' => 'system',
//                         'content' => 'Summarize blog posts in 3-5 concise bullet points.'
//                     ],
//                     [
//                         'role' => 'user',
//                         'content' => $content
//                     ]
//                 ],
//                 'temperature' => 0.3
//             ]),
//             'timeout' => 60,
//         ]
//     );

//     if (is_wp_error($response)) {
//         wp_send_json_error([
//             'message' => $response->get_error_message()
//         ]);
//     }

//     $body = json_decode(
//         wp_remote_retrieve_body($response),
//         true
//     );

//     $summary = $body['choices'][0]['message']['content'] ?? '';

//     wp_send_json_success([
//         'summary' => $summary,
//         'post_id' => $post_id
//     ]);
// }

add_action('wp_ajax_get_post_summary','get_blog_summery');
add_action('wp_ajax_nopriv_get_post_summary','get_blog_summery');
?>