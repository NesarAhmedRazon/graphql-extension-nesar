<?php

/**
 * Plugin Name: Graphql Extension by Nesar
 * Plugin URI: https://github.com/NesarAhmedRazon/graphql-extension-nesar
 * Version: 0.0.1
 * Author: Nesar Ahmed
 * Author URI: https://github.com/NesarAhmedRazon
 * Description: This is an extension plugin for WP-Graphql
 * Text Domain: graphql-extension-nesar
 * WPGraphQL requires at least: 1.6.1+
 * 
 * @package     WPGraphQL\GqlExtNesar
 */

//Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Checks if Graphql Extension by Nesar required plugins are installed and activated
 */
// Plugin Folder Path.
if (!defined('GQL_EXTNESAR_PLUGIN_DIR')) {
    define('GQL_EXTNESAR_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
// Plugin Folder URL.
if (
    !defined('GQL_EXTNESAR_PLUGIN_URL')
) {
    define('GQL_EXTNESAR_PLUGIN_URL', plugin_dir_url(__FILE__));
}
// Plugin Root File.
if (!defined('GQL_EXTNESAR_PLUGIN_FILE')) {
    define('GQL_EXTNESAR_PLUGIN_FILE', __FILE__);
}
function gqlExtis_deps_not_ready()
{
    $deps = array();
    if (!class_exists('\WPGraphQL')) {
        $deps[] = 'WPGraphQL';
    }

    return $deps;
}

function gqlExt_init()
{
    $not_ready = gqlExtis_deps_not_ready();
    if (empty($not_ready)) {
        require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/gql/class-gql-ext-nesar.php';
    }
    return false;
}
add_action('graphql_init', 'gqlExt_init');

require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/gql/types.php';
require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/shortcode/theme/shortcodes.php';
remove_filter('the_content', 'wpautop');
add_shortcode('section', ['ThemeShortCodes', 'section']);

// Total Count Extention for GQL
require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/gql/total-counts-for-wp-graphql.php';
require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/gql/wp-graphql-offset-pagination.php';
if (!\class_exists('\WPGraphQL\Extensions\OffsetPagination')) {
    \WPGraphQL\Extensions\OffsetPagination\Loader::init();
}

// function get_all_post($slug_name = '', $hierac)
// {
//     if ($slug_name !== "") {
//         $url = [];
//         $pars = [];
//         $args = [
//             'post_type' => $slug_name,
//             'posts_per_page' => -1,
//             // 'orderby'          => 'date',
//             // 'order'            => 'DESC',
//         ];
//         $all_posts = get_posts($args);
//         foreach ($all_posts as $post) {
//             $post->post_parent != 0 && array_push($pars, $post->post_parent);
//         }
//         foreach ($all_posts as $post) {
//             if (($hierac === 'orphan') && (!in_array($post->ID, $pars) && $post->post_parent === 0)) {
//                 array_push($url, $post->post_name . '+' . $post->post_parent);
//             } elseif (($hierac === 'parents') && (in_array($post->ID, $pars) && $post->post_parent === 0)) {
//                 array_push($url, $post->post_name . '+' . $post->post_parent);
//             } elseif (($hierac === 'children') && (in_array($post->post_parent, $pars))) {
//                 array_push($url, $post->post_name . '+' . $post->post_parent);
//             } elseif ($hierac === 'none') {
//                 array_push($url, $post->post_name . '+' . $post->post_parent);
//             }
//         }
//         return $url;
//     } else {
//         return null;
//     }
// }
// var_dump(get_all_post('page', 'parents'));
