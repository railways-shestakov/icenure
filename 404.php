<?php
http_response_code(404);
include_once 'variables.php';
get_header();
?>
<main class='main main-404'>
  <section>
    <div>
      <?=get_translation('Сторінка не знайдена', 'Page not found')?>
      <img src='<?=ROOT?>/images/404.svg'>
    </div>
  </section>
</main>
<?php
get_footer();
die();
?>