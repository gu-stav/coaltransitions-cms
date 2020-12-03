<?php

$PRODUCTION_URL = 'https://coaltransitions.org';

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
      'show_in_graphql' => true,
      'graphql_single_name' => 'publication',
      'graphql_plural_name' => 'publications',
      'supports' => array(
        'title',
        'thumbnail',
        'revisions',
      )
    )
  );

  register_post_type('findings',
    array(
      'labels' => array(
        'name' => 'Findings',
        'singular_name' => 'Finding',
        'add_new' => 'New Finding',
        'add_new_item' => 'Add New Finding'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'findings'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-admin-comments',
      'show_in_graphql' => true,
      'graphql_single_name' => 'finding',
      'graphql_plural_name' => 'findings',
      'supports' => array(
        'title',
        'thumbnail',
        'revisions',
      )
    )
  );

  register_post_type('researchprojects',
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
      'show_in_graphql' => true,
      'graphql_single_name' => 'researchProject',
      'graphql_plural_name' => 'researchProjects',
      'supports' => array(
        'title',
        'revisions',
      )
    )
  );

  register_post_type('researchers',
    array(
      'labels' => array(
        'name' => 'Researchers',
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
      'show_in_graphql' => true,
      'graphql_single_name' => 'researcher',
      'graphql_plural_name' => 'researchers',
      'supports' => array(
        'title',
        'revisions',
      )
    )
  );

  register_post_type('news',
    array(
      'labels' => array(
        'name' => 'News',
        'singular_name' => 'News',
        'add_new' => 'Create News Entry',
        'add_new_item' => 'Add News Entry'
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array(
        'slug' => 'news'
      ),
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-format-aside',
      'taxonomies' => array('news_tags'),
      'show_in_graphql' => true,
      'graphql_single_name' => 'newsEntry',
      'graphql_plural_name' => 'news',
      'supports' => array(
        'title',
        'revisions',
      )
    )
  );

  register_taxonomy('news_tags', array('news'), array(
    'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_graphql' => true,
    'graphql_single_name' => 'newsTag',
    'graphql_plural_name' => 'newsTags',
    'rewrite' => array( 'slug' => 'tags' ),
  ));
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
  register_nav_menu('menu', 'Menu');
  register_nav_menu('footer', 'Footer');
}

function coaltransitions_remove_page_features() {
  remove_post_type_support('page', 'editor');
}

add_action('init', 'register_custom_nav_menus');
add_action('init', 'coaltransitions_register_post_types');
add_action('init', 'coaltransitions_remove_page_features');
add_action('save_post', 'trigger_netlify_deploy');
add_action('admin_menu','cleanup_admin');
add_action('admin_bar_menu', 'custom_visit_site_url', 80);

add_theme_support('post-thumbnails', ['page']);

?>
