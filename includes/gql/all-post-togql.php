<?php

function addAllPostTypeInGQL()
{
    $types = getAllpostTypes();

    foreach ($types as $slug => $name) {

        $postType = get_post_type_object($slug);
        if (!array_key_exists("show_in_graphql", $postType)) {

            $postType->show_in_graphql = true;
            $postType->graphql_single_name = $name;
            $postType->graphql_plural_name = $name . 's';
        }
        register_post_type($slug, $postType);
    }
}
add_action('init', 'addAllPostTypeInGQL', 100);


function getAllpostTypes()
{
    $items = [];
    $ignors = ['attachment', 'elementor_library', 'elementor-thhf', 'e-landing-page', 'rslide'];
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
