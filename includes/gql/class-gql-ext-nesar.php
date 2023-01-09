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

            register_graphql_enum_type('Type', [
                'description' => __('The Type of Identifier used to fetch a single node. Default is "ID". To be used along with the "id" field.', 'graphql-extension-nesar'),
                'values'      => [
                    'ALL'          => [
                        'name'        => 'ALL',
                        'value'       => 'all',
                        'description' => __('Get all uri without any hierarchy', 'graphql-extension-nesar'),
                    ],
                    'PARENT' => [
                        'name'        => 'PARENT',
                        'value'       => 'parent',
                        'description' => __('Identify a menu node by the Database ID.', 'graphql-extension-nesar'),
                    ],
                    'CHILDREN'    => [
                        'name'        => 'CHILDREN',
                        'value'       => 'children',
                        'description' => __('Identify a menu node by the slug of menu location to which it is assigned', 'graphql-extension-nesar'),
                    ],
                    'ORPHAN'    => [
                        'name'        => 'ORPHAN',
                        'value'       => 'orphan',
                        'description' => __('Identify a menu node by the slug of menu location to which it is assigned', 'graphql-extension-nesar'),
                    ]
                ],
            ]);

            register_graphql_field('RootQuery', 'allUri', [
                'description' => __('Query All Pages and Post URI', 'graphql-extension-nesar'),
                'type' => ['list_of' => 'String'],
                'args' => [
                    'postType' => [
                        'description' => __('Which Post Type you want to Query', 'graphql-extension-nesar'),
                        'type' => 'String',
                        'defaultValue' => 'all'
                    ],
                    'filter' => [
                        'description' => __('Which Post Type you want to Query', 'graphql-extension-nesar'),
                        'type' => 'Type',
                        'defaultValue' => 'all'
                    ],
                    'exclude' => [
                        'description' => __('Which Post Type you want to Query', 'graphql-extension-nesar'),
                        'type' => ['list_of' => 'String'],

                    ],
                ],
                'resolve' => function ($root, $args, $contex, $info) {
                    $data = $this->makeUri($args['postType'], $args['filter']);
                    return $data;
                }
            ]);
        }

        public function makeUri($postType = '', $filter = 'all')
        {
            $allUri = [];
            if ($postType == 'all') {

                $PostTypes = $this->getAllpostTypes();
                foreach ($PostTypes as $type => $slug) {
                    $posts = $this->get_all_post($type, $filter);
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

        public function get_all_post($slug_name = '', $filter)
        {
            if ($slug_name !== "") {
                $url = [];
                $parent = [];
                $args = [
                    'post_type' => $slug_name,
                    'posts_per_page' => -1,
                    // 'orderby'          => 'date',
                    // 'order'            => 'DESC',
                ];
                $all_posts = get_posts($args);
                foreach ($all_posts as $post) {
                    if ($post->post_parent !== 0) {
                        array_push($parent, $post->post_parent);
                    }
                }
                $parents = array_unique($parent, SORT_REGULAR);
                foreach ($all_posts as $post) {
                    global $wp_post_types;



                    $id = $post->ID;
                    $pid = $post->post_parent;
                    $ptype = $post->post_type;
                    $obj = $wp_post_types[$ptype];
                    $cc =  $obj->query_var;
                    if (($filter === 'orphan') && (!in_array($id, $parents) && !in_array($pid, $parents))) {
                        if (($ptype === 'post') || ($ptype === 'page')) {
                            array_push($url, $this->createUrl($post));
                        } else {
                            array_push($url, $cc . '/' . $this->createUrl($post));
                        }
                    } elseif (($filter === 'parent') && (in_array($id, $parents) && !in_array($pid, $parents))) {
                        array_push($url, $this->createUrl($post));
                    } elseif ($filter === 'children' && in_array($pid, $parents)) {
                        array_push($url, $this->createUrl($post));
                    } elseif ($filter === 'all') {
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
