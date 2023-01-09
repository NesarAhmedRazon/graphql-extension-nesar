<?php

class hierarchyTypeEnum
{

    /**
     * Register the hierarchyTypeEnum
     *
     * @return void
     */
    public function __construct()
    {
        add_action('graphql_register_types', [$this, 'register_type']);
    }
    public static function register_type()
    {
        register_graphql_enum_type('Hierarchy', [
            'description' => __('The Type of Identifier used to fetch a single node. Default is "ID". To be used along with the "id" field.', 'wp-graphql'),
            'values'      => [
                'ALL'          => [
                    'name'        => 'ALL',
                    'value'       => 'all',
                    'description' => __('Get all uri without any hierarchy', 'wp-graphql'),
                ],
                'PARENTS' => [
                    'name'        => 'PARENTS',
                    'value'       => 'parents',
                    'description' => __('Identify a menu node by the Database ID.', 'wp-graphql'),
                ],
                'CHILDREN'    => [
                    'name'        => 'CHILDREN',
                    'value'       => 'children',
                    'description' => __('Identify a menu node by the slug of menu location to which it is assigned', 'wp-graphql'),
                ],
                'ORPHAN'    => [
                    'name'        => 'ORPHAN',
                    'value'       => 'orphan',
                    'description' => __('Identify a menu node by the slug of menu location to which it is assigned', 'wp-graphql'),
                ]
            ],
        ]);
    }
}
new hierarchyTypeEnum();