<?php
/*
  Template Name: Публікації Q1/Q2
*/

include_once 'variables.php';

$args = array(
    'numberposts' => -1,
    'post_type' => 'articles_and_reports',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'quartile',
            'compare' => 'REGEXP',
            'value' => '[qQ][12]'
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'student-publication',
                'value' => '0'
            ),
            array(
                'key' => 'student-publication',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => 'authors-relationship',
                'compare' => 'REGEXP',
                'value' => '^a:[123456789]'
            )
        )
    ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
);

$context = create_context($args);
$context['title'] = get_translation('Публікації Q1/Q2', 'Publications Q1/Q2');
$template = 'common/publications-q12.twig';

get_header();
?>
    <main class='main main-sidebar'>
        <?php
        Timber::render($template, $context);

        if (!IS_MOBILE)
            get_sidebar('science');
        ?>
    </main>
<?php
get_footer();
?>
