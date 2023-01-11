<?php
defined('ABSPATH') || exit;


//Add Entypo Font
add_action('wp_enqueue_scripts', 'entypo_fonts');

if (!function_exists('entypo_fonts')) {
    function entypo_fonts()
    {
        wp_enqueue_style('regen_entypo', 'http://cdn.zap.be/css/entypo.css', array(), '', '');
        wp_enqueue_style('regen_entypo');
    }
}
