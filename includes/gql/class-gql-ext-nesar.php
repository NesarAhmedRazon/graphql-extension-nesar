<?php

/**
 * Initializes a singleton instance of GQL_Ext_Nesar
 * Text Domain: graphql-extension-nesar
 * @package WPGraphQL\GqlExtNesar
 * @since 0.0.1
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
if (!class_exists('GQL_Ext_Nesar')) {
    class GQL_Ext_Nesar
    {
        public function __construct()
        {
            add_action('graphql_register_types', [$this, 'addToGraphQl']);
        }
        public function addToGraphQl()
        {
            register_graphql_field('RootQuery', 'allUri', [
                'description' => __('Query All Pages and Post URI', 'graphql-extension-nesar'),
                'type' => ['list_of' => 'String'],
                'args' => [
                    'postType' => [
                        'description' => __('Which Post Type you want to Query', 'graphql-extension-nesar'),
                        'type' => 'String',
                        'defaultValue' => 'all'

                    ],
                ],
                'resolve' => function ($root, $args, $contex, $info) {
                    $data = $this->makeUri($args['postType']);
                    return $data;
                }
            ]);
        }

        public function makeUri($postType = '')
        {
            if ($postType == 'all') {
                $allUri = [];
                $PostTypes = $this->getAllpostTypes();
                foreach ($PostTypes as $type => $slug) {
                    $posts = $this->get_all_post($type);
                    foreach ($posts as $url) {
                        array_push($allUri, $url);
                    }
                }
                return $allUri;
            }
        }
        public function createUrl($post)
        {
            $pid = $post->post_parent;
            $url = $post->post_name;
            if ($pid != 0) {
                $par = get_post($pid, OBJECT, 'raw');
                $parent = $this->createUrl($par);
                $url = $parent . '/' . $url;
            }

            return $url;
        }

        public function get_all_post($slug_name = '')
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

                    array_push($url, $this->createUrl($post));
                }
                return $url;
            } else {
                return null;
            }
        }
        public function getAllpostTypes()
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
    }
}
$next_ssr = new GQL_Ext_Nesar();