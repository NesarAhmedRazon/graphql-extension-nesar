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


            $html = '</div></div><div class="' . $class . '" ' . $style . '>' . do_shortcode($content) . '</div>';
            return $html;
        }
    }
}


if (!function_exists('bluevaga_spacer')) {
add_action( 'wp_loaded', 'overWriteSpacer',20 );
function overWriteSpacer() {
	function newSpacer($atts, $content = null) {
        $spacer_output = '';
        $main='';
        $id = 'spacer_'.uniqid();
        extract(shortcode_atts(array(
			'size' => '',
			'mobile' => '',
			'md' => '',
			'lg' => '',
			'xl' => '',
            'xxl' => '',
		), $atts));

        if ($size) {
			if (!strstr($size, 'px')) {
				$size = $size . "px";
			}
            $style = '#'.$id.'{margin-top:'.$size.';}';
		}

        if ($mobile) {
			if (!strstr($mobile, 'px')) {
				$mobile = $mobile . "px";
			}
            //Tailwind Css sm:mt-[$mobile]
			$style .= '@media (max-width: 640px) {#'.$id.'{margin-top:'.$mobile.';}}';
		}

        if ($md) { 
			if (!strstr($md, 'px')) {
				$md = $md . "px";
			}
            //Tailwind Css md:mt-[$md]
			$style .= '@media (min-width: 768px) {#'.$id.'{margin-top:'.$md.';}}';
		}
        if ($lg) {
			if (!strstr($lg, 'px')) {
				$lg = $lg . "px";
			}
            //Tailwind Css lg:mt-[$lg]
			$style .= '@media (min-width: 1024px) {#'.$id.'{margin-top:'.$lg.';}}';
		}
        if ($xl) {
			if (!strstr($xl, 'px')) {
				$xl = $xl . "px";
			}
            //Tailwind Css xl:mt-[$xl]
			$style .= '@media (min-width: 1280px) {#'.$id.'{margin-top:'.$xl.';}}';
		}
        if ($xxl) {
			if (!strstr($xxl, 'px')) {
				$xxl = $xxl . "px";
			}
            //Tailwind Css 2xl:mt-[$xxl]
			$style .= '@media (min-width: 1536px) {#'.$id.'{margin-top:'.$xxl.';}}';
		}


        // Make the Spacer
        if ($size == 'none' || $size == '' || $size == ' ') {
			$spacer_output .= '';
		} else {
            $spacer_output .= '<style>'.$style.'</style>';
		}

		return '<div class="spacer" id="'.$id.'">'.$spacer_output.'</div>';
	}
	remove_shortcode( 'spacer' );
	add_shortcode( 'spacer', 'newSpacer' );
}
}