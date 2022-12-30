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

function gqlExtis_deps_not_ready()
{
    $deps = array();
    if (!class_exists('\WPGraphQL')) {
        $deps[] = 'WPGraphQL';
    }

    return $deps;
}

function gqlExt_init()
{ // Plugin Folder Path.
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
    $not_ready = gqlExtis_deps_not_ready();
    if (empty($not_ready)) {
        require_once GQL_EXTNESAR_PLUGIN_DIR . 'includes/class-gql-ext-nesar.php';
    }
    return false;
}
add_action('graphql_init', 'gqlExt_init');

function createUrl($post)
{

    $pid = $post->post_parent;
    $url = $post->post_name;
    if ($pid != 0) {
        $par = get_post($pid, OBJECT, 'raw');
        $parent = createUrl($par);
        $url = $parent . '/' . $url;
    }
    return $url;
}

function get_all_post($slug_name = '')
{
    if ($slug_name !== "") {
        $url = [];
        $args = [
            'post_type' => $slug_name,
            'posts_per_page' => -1,
            // 'orderby'          => 'date',
            // 'order'            => 'DESC',
        ];
        $all_posts = get_posts($args);
        foreach ($all_posts as $post) {

            array_push($url, createUrl($post));
        }

        return $url;
        //wp_send_json($all_posts);
        //wp_die();
    } else {
        return null;
    }
}

function get_allPostsFromAllpostTypes()
{
    $items = [];
    $ignors = ['attachment', 'elementor_library', 'elementor-thhf', 'e-landing-page'];
    $args = array(
        'public'   => true,
    );
    $post_types = get_post_types($args, 'objects');

    foreach ($ignors as $ignor) {
        unset($post_types[$ignor]);
    }
    if ($post_types) {
        foreach ($post_types  as $post_type) {
            $k = $post_type->name;
            $v = $post_type->label;
            $items[$k] = esc_html__($v, 'nesar-widgets');
        }
        return $items;
    }
}

function makeUri($postType = '')
{
    if ($postType == 'all') {
        $allUri = [];
        $PostTypes = get_allPostsFromAllpostTypes();
        foreach ($PostTypes as $type => $slug) {
            $posts = get_all_post($type);
            var_dump($posts);
            echo '<br><br>';
            foreach ($posts as $url) {
                array_push($allUri, $url);
            }
        }
        return implode('|', $allUri);
    }
}
//var_dump(makeUri('all'));
