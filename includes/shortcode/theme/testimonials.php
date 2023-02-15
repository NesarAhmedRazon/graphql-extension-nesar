<?php

/**
 * Testimonials List Shortcode
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('testimonials_list')) {
    function the_list($atts, $content=''){
        
        extract(shortcode_atts(array(
            'column' => '',
            'class' => '',
            'style' => '',
        ), $atts));
        
        if ($class) {
            $class = ' '.$class;
        }
        

        return '<div class="testimonials_list'.$class.'" >'. do_shortcode($content) .'</div>'; 
    }
    function the_card($atts, $content=''){
        
        extract(shortcode_atts(array(
            'class' => '',
            'title'=>'',
            'link'=>'#',
            'name'=>'',
            'org' => '',
            'tag' => '',
        ), $atts));
        
        if ($class) {
            $class = ' '.$class;
        }
        if ($title) {
            $title = ', '.$title;
        }
        if ($name) {
            $name = '<p class="name">- '.$name. $title.'</p>';
        }
        if ($org) {
            $org = '<p class="orgs">'.$org.'</p>';
        }
        if ($tag) {
            $tag = '<p class="tags">'.$tag.'</p>';
        }
        if($link){
            $readmore = '<p class="read-more">Read Full Story</p>';
        }else{
            $readmore='' ;
        }
        $body = '<div class="testimonial-content"><p class="testimonial-text">'. do_shortcode($content) .'</p></div>';
        $body .= '<div class="testimonial-footer">'.$name.$org.$tag.$readmore.'</div>';
        
        if($link){
            $card = '<a class="testimonial-item'.$class.'" href="'.$link.'">'.$body.'</a>';
        }else{
            $card = '<div class="testimonial-item'.$class.'" >'.$body.'</div>';
        }

        return $card; 
    }
    add_shortcode( 'testimonials', 'the_list' );
    add_shortcode( 'testimonial', 'the_card' );
}