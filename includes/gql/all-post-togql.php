<?php


function addTimeLineGql()
{
    $slug1 = 'cool_timeline';
    $postType1 = get_post_type_object($slug1);
    if ($postType1 != null && !array_key_exists("show_in_graphql", $postType1)) {
        $postType1->show_in_graphql = true;
        $postType1->graphql_single_name = 'Timeline';
        $postType1->graphql_plural_name = 'Timelines';
    }
    register_post_type($slug1, $postType1);
}
add_action('init', 'addTimeLineGql', 100);

function addFaqGql()
{
    $slug3 = 'faq';
    $postType3 = get_post_type_object($slug3);
    if ($postType3 != null && !array_key_exists("show_in_graphql", $postType3)) {
        $postType3->show_in_graphql = true;
        $postType3->graphql_single_name = 'FAQ';
        $postType3->graphql_plural_name = 'FAQs';
    }
    register_post_type($slug3, $postType3);
}
add_action('init', 'addFaqGql', 100);
function addPortfolioGql()
{
    $slug2 = 'portfolio';
    $postType2 = get_post_type_object($slug2);
    if ($postType2 != null && !array_key_exists("show_in_graphql", $postType2)) {
        $postType2->show_in_graphql = true;
        $postType2->graphql_single_name = 'Portfolio';
        $postType2->graphql_plural_name = 'Portfolios';
    }
    register_post_type($slug2, $postType2);
}
add_action('init', 'addPortfolioGql', 100);

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
