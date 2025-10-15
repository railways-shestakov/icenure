<?php
/*
  Template Name: Загальний перелік монографій
*/

// GLOBAL VARIABLES

include_once 'variables.php';

// LOCAL VARIABLES

$monographs_args = array(
    'numberposts' => -1,
    'post_type' => 'monographs',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

$monographs_chapters_args = array(
    'numberposts' => -1,
    'post_type' => 'articles_and_reports',
    'meta_query' => array(
        array(
            'key' => 'monograph',
            'value' => '1',
        ),
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

$monographs_chapters = Timber::get_posts($monographs_chapters_args);
$monographs = Timber::get_posts($monographs_args);




$context = create_context($monographs_args);
$context['title'] = get_translation('Загальний перелік монографій', 'General list of monographs');

$monographs_list = array();

foreach ($monographs as &$value) {
    $monographs_list[] = array(
        'text' => get_field('bibliographic_ref', $value->ID),
        'url' => get_field('url', $value->ID),
        'year' => get_field('year', $value->ID),
    );
}

foreach ($monographs_chapters as &$value) {
    $monographs_list[] = array(
        'text' => get_field('authors', $value->ID) . ' ' . get_field('text', $value->ID),
        'url' => get_field('doi', $value->ID),
        'year' => get_field('year', $value->ID),
    );
}

usort($monographs_list, function($first, $second) {
    if($first['year'] == $second['year']) {
        return $first['text'] > $second['text'];
    }
    return $first['year'] > $second['year'];
}); 

$context['monographs'] = $monographs_list;

$template = 'common/general-list-of-monographs.twig';

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