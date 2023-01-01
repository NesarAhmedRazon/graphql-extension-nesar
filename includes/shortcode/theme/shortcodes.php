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
        public static function section($atts, $content = null)
        {
            extract(shortcode_atts(['class' => '', 'style' => '',], $atts));
            if ($style) {
                $style = "style='" . $atts['style'] . "' ";
            }
            if ($class) {
                $class = " " . $atts['class'];
            }


            $html = '</div></div><div class="containerSized section row' . $class . '" ' . $style . '>' . do_shortcode($content) . '</div>';
            return $html;
        }
    }
}
