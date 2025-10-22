<?php
// Place this file in your WordPress root (same dir as wp-load.php)
require_once __DIR__ . '/wp-load.php';

if (!function_exists('wp_insert_post')) {
    http_response_code(500);
    exit('WordPress failed to load');
}

$post_id = wp_insert_post([
    'post_title'   => '이것은 제목입니다',
    'post_content' => 'autopost.php 작동 성공',
    'post_status'  => 'publish',
    'post_author'  => 1,
]);

if (is_wp_error($post_id)) {
    http_response_code(500);
    exit($post_id->get_error_message());
}

echo 'OK ' . $post_id . ' ' . get_permalink($post_id);
