<?php
/*
Plugin Name: Word Counter Plus
Description: Track and display word count for WordPress posts, aiding in content creation and editing efficiency. Count words from Dashboard → Posts column.
Version: 1.0.0
Author: Mofizul Islam
Author URI: http://mofizul.com/
License: GPL2 or later
Text Domain: word-counter-plus
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit; 
}

// Load text domain
function wcp_wordcount_load_textdomain()
{
    load_plugin_textdomain('word-counter-plus', false, dirname(__FILE__) . '/languages');
}
add_action("plugins_loaded", "wcp_wordcount_load_textdomain");

// Add a new column in the admin post list
function wcp_custom_posts_columns($columns)
{
    $columns['word_count'] = __('Word Count', 'word-counter-plus');
    return $columns;
}
add_filter('manage_posts_columns', 'wcp_custom_posts_columns');

// Fill the new column with the word count for each post
function wcp_custom_posts_custom_column($column_name, $post_id)
{
    if ('word_count' === $column_name) {
        $content = get_post_field('post_content', $post_id);
        $word_count = wcp_custom_count_words($content);
        echo esc_attr($word_count);
    }
}
add_action('manage_posts_custom_column', 'wcp_custom_posts_custom_column', 10, 2);

// Function that closely mimics the WordPress editor word count
function wcp_custom_count_words($content)
{
    $content = strip_shortcodes($content);
    $content = wp_strip_all_tags($content);
    $content = preg_replace("/\s+/", ' ', $content);
    $content = explode(' ', $content);
    $content = array_filter($content);
    return count($content);
}

// Make the Word Count column sortable
function wcp_custom_posts_column_sortable($columns)
{
    $columns['word_count'] = 'word_count';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'wcp_custom_posts_column_sortable');

