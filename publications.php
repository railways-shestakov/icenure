<?php
/*
  Template Name: Публікації
*/

// GLOBAL VARIABLES

include_once 'variables.php';

// LOCAL VARIABLES

$args = array(
    'numberposts' => -1,
    'post_type' => 'articles_and_reports',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'student-publication',
            'value' => '0',
        ),
        array(
            'key' => 'student-publication',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key' => 'authors-relationship',
            'compare' => 'REGEXP',
            'value' => '^a:[123456789]',
        ),
//
    ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
);

$context = create_context($args);
$context['title'] = get_translation('Публікації', 'Publications');
$template = 'common/publications.twig';

// HEADER

get_header();
?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">

    <main class='main main-sidebar publications'>
        <?php
        // PAGE CONTENT

        Timber::render($template, $context);

        // SIDEBAR

        if (!IS_MOBILE)
            get_sidebar('science');
        ?>
    </main>

<?php
// FOOTER

get_footer();
?>