<?php

/*
Plugin Name: Flexslider wp - plugin
Plugin URI:
Description: Simple plugin for slide your post thumbnail image
Version: 1.0
Author: Yuriy Stenin html-and-cms
Author URI: http://html-and-cms.com
License: GPL
*/
/*  Copyright 2015  Yuriy Stenin  (email : yuriy.ipcom {at} gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('EFS_PATH', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/');
define('EFS_NAME', "Envato FlexSlider");
/**
 * Registration js and css */
wp_enqueue_script('flexslider', EFS_PATH . 'jquery.flexslider-min.js', array('jquery'));
wp_enqueue_script('flexslider', EFS_PATH . 'jquery.flexslider.js', array('jquery'));
wp_enqueue_script('script', EFS_PATH . 'script.js', array('jquery'));
wp_enqueue_style('flexslider_css', EFS_PATH . 'flexslider.css');

/**
 ** Added content type Slider Image*/
function fs_register()
{
    $args = array(
        'label' => __('Slider image'),
        'singular_label' => __('Slider Image'),
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'rewrite' => true,
        'supports' => array('title', 'thumbnail')
    );

    register_post_type('slider-image', $args);
}

add_action('init', 'fs_register');

function fs_get_slider()
{

    $slider = '<div class="flexslider">
      <ul class="slides">';

    $fs_query = "post_type=slider-image";
    query_posts($fs_query);

    if (have_posts()) : while (have_posts()) : the_post();
        $img = get_the_post_thumbnail(get_the_ID(), 'large');
        $body = get_the_title();

        $slider .= '<li>' . $body . $img . '</li>';

    endwhile; endif;
    wp_reset_query();

    $slider .= '</ul>
    </div>';
    return $slider;


}

/**add the shortcode for the slider- for use in editor**/

function fs_insert_slider()
{

    $slider = fs_get_slider();

    return $slider;

}

add_shortcode('f_slider', 'fs_insert_slider');

/**add template tag- for use in themes**/

function fs_slider()
{
    print fs_get_slider();
}
class Slider extends WP_Widget
{

    function Slider()
    {
        // Instantiate the parent object
        parent::__construct(false, __('Slider widget'));
    }

    function widget($args, $instance)
    { ?>
        <?php
        $slider = '<div class="flexslider">
    <ul class="slides">';

        $fs_query = "post_type=slider-image";
        query_posts($fs_query);

        if (have_posts()) : while (have_posts()) : the_post();
            $img = get_the_post_thumbnail(get_the_ID(), 'large');
            $body = get_the_title();

            $slider .= '<li>' . $body . $img . '</li>';

        endwhile; endif;
        wp_reset_query();

        $slider .= '</ul>
  </div>';
        echo $slider;


    }

}
add_action( 'widgets_init', function(){
    register_widget( 'Slider' );
});
