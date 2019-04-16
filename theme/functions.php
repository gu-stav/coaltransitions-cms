<?php

$PRODUCTION_URL = 'https://coaltransitions.netlify.com';
$BUILD_HOOK_URL = 'https://api.netlify.com/build_hooks/5cb587cfd6fd64bb48a15409';

function bruderland_register_post_types() {
  register_post_type('publications',
    array(
      'labels' => array(
        'name' => 'Publications',
        'singular_name' => 'Publication',
        'add_new' => 'New Publication',
        'add_new_item' => 'Add New Publication'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'publications'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-format-aside',
      'taxonomies' => array('post_tag'),
      'supports' => array(
        'title',
        'thumbnail',
        'revisions',
      )
    )
  );
}

// see https://plugins.trac.wordpress.org/browser/wp-gatsby/trunk/class-wp-gatsby.php
function trigger_netlify_deploy() {
  wp_remote_post($BUILD_HOOK_URL);
}

function cleanup_admin() {
  remove_menu_page('edit.php');
  remove_menu_page('edit-comments.php');
}

function custom_visit_site_url($wp_admin_bar) {
  global $PRODUCTION_URL;
  // Get a reference to the view-site node to modify.
  $node = $wp_admin_bar->get_node('view-site');
  $node->meta['target'] = '_blank';
  $node->meta['rel'] = 'noopener noreferrer';
  $node->href = $PRODUCTION_URL;
  $wp_admin_bar->add_node($node);
  // Site name node
  $node = $wp_admin_bar->get_node('site-name');
  $node->meta['target'] = '_blank';
  $node->meta['rel'] = 'noopener noreferrer';
  $node->href = $PRODUCTION_URL;
  $wp_admin_bar->add_node($node);
}

function update_post_links($permalink, $post) {
  if(get_post_type($post) == 'episodes') {
    $permalink = home_url('/episodes/'.$post->post_name);
  }

  if(get_post_type($post) == 'protagonists') {
    $permalink = home_url('/protagonists/'.$post->post_name);
  }
  return $permalink;
}

add_action('init', 'bruderland_register_post_types');
add_action('save_post', 'trigger_netlify_deploy');
add_action('admin_menu','cleanup_admin');
add_action('admin_bar_menu', 'custom_visit_site_url', 80);
add_filter('post_type_link', 'update_post_links', 10, 2) ;

add_theme_support('post-thumbnails');

?>
