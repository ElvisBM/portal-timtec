<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */

define('SLUG', 'portal-timtec');

session_start();

require __DIR__ . '/inc/classes/PrivateFile.php';
require __DIR__ . '/inc/classes/OneToOneMetabox.php';
require __DIR__ . '/inc/classes/OneToManyMetabox.php';
require __DIR__ . '/inc/classes/ManyToManyRelation.php';

require __DIR__ . '/inc/ajax.php';

require __DIR__ . '/inc/post-types/teacher.php';
require __DIR__ . '/inc/post-types/course.php';
require __DIR__ . '/inc/post-types/organization.php';
require __DIR__ . '/inc/post-types/redessociais.php';
//require __DIR__ . '/inc/post-types/installation.php';

require __DIR__ . '/inc/metaboxes/teacher-course-relation.php';
require __DIR__ . '/inc/metaboxes/course-download.php';
require __DIR__ . '/inc/metaboxes/link_redesocial.php';
require __DIR__ . '/inc/metaboxes/list_icon_awesome.php';




$sage_includes = [
  'lib/utils.php',                 // Utility functions
  'lib/init.php',                  // Initial theme setup and constants
  'lib/wrapper.php',               // Theme wrapper class
  'lib/conditional-tag-check.php', // ConditionalTagCheck class
  'lib/config.php',                // Configuration
  'lib/assets.php',                // Scripts and stylesheets
  'lib/titles.php',                // Page titles
  'lib/extras.php',                // Custom functions
  'inc/metaboxes/url-video-course.php', //MetaBox 
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);



/*
 *  ================ REWRITE RULES ================ *
 */
//*

pll_register_string('URL Cursos', 'cursos', 'timtec');

add_action('generate_rewrite_rules', function ($wp_rewrite) {
  $new_rules=[];
  foreach (pll_languages_list() as $lcode) {
    $str_courses = pll_translate_string('cursos', $lcode); 
    $new_rules["^$lcode/$str_courses/?$"] = "index.php?template=courses";
  }
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
  return $wp_rewrite;
});

add_filter('query_vars', function ($public_query_vars) {
  $public_query_vars[] = "template";
  return $public_query_vars;
});

add_action('template_redirect', function() {
  if ($template = get_query_var('template')) {
    if(file_exists(__DIR__ . "/templates/page-{$template}.php")){
      require __DIR__ . "/templates/page-{$template}.php";
      die;
    }
  }
});

// */

/**
* Adicionar Select com Busca de Icones Awesome Post_type RedeSocial
*/

function rede_social_icon_select() {
    global $post_type;
    if(is_admin() && $post_type  ==  'rede_social' ){

        wp_register_script('fontawesomeiconpicker', get_bloginfo('template_directory') .'/dist/scripts/fontawesome-iconpicker.min.js');
        wp_enqueue_script('fontawesomeiconpicker');

        wp_register_script('icon-selector', get_bloginfo('template_directory') .'/dist/scripts/icon-selector.js');
        wp_enqueue_script('icon-selector');

        wp_register_style('fontawesomecss', get_bloginfo('template_directory') .'/dist/styles/font-awesome.min.css');
        wp_enqueue_style('fontawesomecss');

        wp_register_style('fontawesomeiconpickercss', get_bloginfo('template_directory') .'/dist/styles/fontawesome-iconpicker.min.css');
        wp_enqueue_style('fontawesomeiconpickercss');
    }

}

add_action( 'admin_print_scripts-post-new.php', 'rede_social_icon_select', 11 );
add_action( 'admin_print_scripts-post.php', 'rede_social_icon_select', 11 );



/**
* Alterar Nome PostType "Post"  para "Notícias"
**/
add_action( 'admin_menu', 'rename_post_for_noticia_label' );
add_action( 'init', 'rename_post_for_noticia_object' );
function rename_post_for_noticia_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Notícias';
    $submenu['edit.php'][5][0] = 'Notícias';
    $submenu['edit.php'][10][0] = 'Add Notícias';
    $submenu['edit.php'][16][0] = 'Notícias Tags';
    echo '';
}
function rename_post_for_noticia_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Notícias';
    $labels->singular_name = 'Notícias';
    $labels->add_new = 'Add Notícias';
    $labels->add_new_item = 'Add Notícias';
    $labels->edit_item = 'Edit Notícias';
    $labels->new_item = 'Notícias';
    $labels->view_item = 'View Notícias';
    $labels->search_items = 'Search Notícias';
    $labels->not_found = 'No Notícias found';
    $labels->not_found_in_trash = 'No Notícias found in Trash';
    $labels->all_items = 'All Notícias';
    $labels->menu_name = 'Notícias';
    $labels->name_admin_bar = 'Notícias';
}
 

