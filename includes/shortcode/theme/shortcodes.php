<?php

/**
 * Initializes a singleton instance of GQL_Ext_Nesar
 * Text Domain: graphql-extension-nesar
 * @package WPGraphQL\GqlExtNesar
 * @since 0.0.1
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
if (!class_exists('ThemeShortCodes')) {
    class ThemeShortCodes
    {
        public static function container($attrs, $content = "")
        {
            $style = '';
            $class = '';
            if (!is_array($attrs)) {
                $attrs = [];
            }
            $attrs = shortcode_atts(
                [
                    'class' => $class,
                    'style' => $style
                ],
                $attrs,
                'container'
            );
            $attrs['style'] != '' && $style = 'style="' . $attrs['style'] . '"';
            $contains = strpos($content, 'class="row"') !== false;
            if (!$contains) {
                $content = '<div class="row"><div class="twelve columns">' . $content . '</div></div>';
            }
            $html = '<div class="container ' . $attrs['class'] . '" ' . $style . '>' . $content . '</div>';
            return $html;
        }
    }
}
