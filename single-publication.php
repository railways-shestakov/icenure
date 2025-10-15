<?php
// GLOBAL VARIABLES

include_once 'variables.php';

// LOCAL VARIABLES

$year = get_field('year');

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
    ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'year',
    'meta_value' => $year,
    'order' => 'DESC',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
);

$context = create_context($args);
$template = 'common/publication-post.twig';

// HEADER

get_header();
?>

    <main class='main main-sidebar'>
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