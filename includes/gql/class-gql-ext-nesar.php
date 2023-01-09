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
                    'hierarchyType' => [
                        'description' => __('Which Post Hierarchy Type you want to Query', 'graphql-extension-nesar'),
                        'type' => 'Hierarchy',
                        'defaultValue' => 'all'

                    ],

                ],
                'resolve' => function ($root, $args, $contex, $info) {
                    $data = $this->makeUri($args['postType'], $args['hierarchyType']);
                    return $data;
                }
            ]);
        }

        public function makeUri($postType = '', $hierac = 'all')
        {
            $allUri = [];
            if ($postType == 'all') {
                $PostTypes = $this->getAllpostTypes();
                foreach ($PostTypes as $type => $slug) {
                    $posts = $this->get_all_post($type, $hierac);
                    foreach ($posts as $url) {
                        array_push($allUri, $url);
                    }
                }
                return $allUri;
            } else {
                $PostTypes = $this->getAllpostTypes();
                foreach ($PostTypes as $type => $slug) {
                    $posts = $this->get_all_post($type, 'all');
                    foreach ($posts as $url) {
                        $arrUrl = explode('/', $url);
                        if (count($arrUrl) > 1) {
                            if (in_array($postType, $arrUrl)) {
                                $slug = array_shift($arrUrl);
                                if ($slug == $postType) {
                                    $regen = explode('/', $url);
                                    array_shift($regen);
                                    array_push($allUri, implode('/', $regen));
                                }
                            }
                        }
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

        public function get_all_post($slug_name = '', $hierac)
        {
            if ($slug_name !== "") {
                $url = [];
                $pars = [];
                $args = [
                    'post_type' => $slug_name,
                    'posts_per_page' => -1,
                    // 'orderby'          => 'date',
                    // 'order'            => 'DESC',
                ];
                $all_posts = get_posts($args);
                foreach ($all_posts as $post) {
                    $post->post_parent != 0 && array_push($pars, $post->post_parent);
                }
                foreach ($all_posts as $post) {
                    if (($hierac === 'orphan') && (!in_array($post->ID, $pars) && $post->post_parent === 0)) {
                        array_push($url, $this->createUrl($post));
                    } elseif (($hierac === 'parents') && (in_array($post->ID, $pars) && $post->post_parent === 0)) {
                        array_push($url, $this->createUrl($post));
                    } elseif (($hierac === 'children') && (in_array($post->post_parent, $pars))) {
                        array_push($url, $this->createUrl($post));
                    } elseif ($hierac === 'all') {
                        array_push($url, $this->createUrl($post));
                    }
                }
                return $url;
            } else {
                return null;
            }
        }
        public function getAllpostTypes()
        {
            $items = [];
            $ignors = ['attachment', 'elementor_library', 'elementor-thhf', 'e-landing-page', 'blog'];
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
