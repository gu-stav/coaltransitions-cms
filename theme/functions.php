<?php

$PRODUCTION_URL = 'https://coaltransitions.netlify.com';
$BUILD_HOOK_URL = 'https://api.netlify.com/build_hooks/5cd2d01863a2f25c5cbcf2c7';

function coaltransitions_register_post_types() {
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

  register_post_type('coal-phase-out',
    array(
      'labels' => array(
        'name' => 'Coal Phase-Out',
        'singular_name' => 'Coal Phase-Out',
        'add_new' => 'New Fact',
        'add_new_item' => 'Add New Fact'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'coal-phase-out'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-admin-comments',
      'supports' => array(
        'title',
        'thumbnail',
        'revisions',
      )
    )
  );

  register_post_type('research-projects',
    array(
      'labels' => array(
        'name' => 'Research Projects',
        'singular_name' => 'Research Project',
        'add_new' => 'New Research Project',
        'add_new_item' => 'Add New Research Project'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'research-project'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-admin-site',
      'supports' => array(
        'title',
        'revisions',
      )
    )
  );

  register_post_type('researcher',
    array(
      'labels' => array(
        'name' => 'Researcher',
        'singular_name' => 'Researcher',
        'add_new' => 'New Researcher',
        'add_new_item' => 'Add New Researcher'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'researcher'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-admin-users',
      'supports' => array(
        'title',
        'revisions',
      )
    )
  );
}

// see https://plugins.trac.wordpress.org/browser/wp-gatsby/trunk/class-wp-gatsby.php
function trigger_netlify_deploy() {
  global $BUILD_HOOK_URL;

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

function register_custom_nav_menus() {
  register_nav_menus('navigation', 'Navigation');
}

add_action('after_setup_theme', 'register_custom_nav_menus');
add_action('init', 'coaltransitions_register_post_types');
add_action('save_post_publications', 'trigger_netlify_deploy');
add_action('admin_menu','cleanup_admin');
add_action('admin_bar_menu', 'custom_visit_site_url', 80);

add_theme_support('post-thumbnails');

?>
