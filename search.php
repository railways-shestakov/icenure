<?php
    /*
        Template Name: Пошук
    */

    // GLOBAL VARIABLES
    
    include_once 'variables.php';

    // LOCAL VARIABLES

    $search_query = esc_sql(urldecode($wp_query->query_vars['query']));
    $search_query = strip_tags($search_query);
    $search_query = htmlentities($search_query, ENT_QUOTES, "UTF-8");
    $search_query = htmlspecialchars($search_query, ENT_QUOTES);
  
    $context = create_context();
    $context['title'] = get_translation('Пошук новин', 'News search');
    $context['query'] = $search_query;
    $template = 'common/search.twig';

    // HEADER

    get_header();
?>

<main class='main'>
    <?php
        // PAGE CONTENT
    
        Timber::render($template, $context);
    ?>
</main>

<?php
    // FOOTER

    get_footer();
?>