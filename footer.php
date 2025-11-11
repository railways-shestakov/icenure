<?php
  // GLOBAL VARIABLES

  include_once 'variables.php';
  
  // RENDER

  $args = array(
    'numberposts'      => -1,
    'post_type'        => 'teachers',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false
  );
  
  $context = create_context($args);
  $template = DEVICE . '/footer.twig';

  Timber::render($template, $context);
?>

<?php
  if( is_user_logged_in() ) wp_footer();
?>

<div class="popup">

  <div class="popup__box">

    <div class="popup__top">

      <div class="popup__title popup__title--general">
        <?= get_translation('Інформація абітурієнту', 'Applicant information'); ?>
      </div>

      <div class="popup__media">
        
        <div class="footer__icon icon">
          <a class='footer__link' href="https://www.facebook.com/ICEKhNURE" target='blank'></a>
          <img src="/wp-content/themes/nure/images/icons/facebook.png" alt="">
        </div>

        <div class="footer__icon icon">
          <a class='footer__link' href="https://www.instagram.com/ice_nure/" target='blank'></a>
          <img src="/wp-content/themes/nure/images/icons/instagram.png" alt="">
        </div>

        <div class="footer__icon icon">
          <a class='footer__link' href="https://www.youtube.com/channel/UCbHGJc4QVanbj50q9eRxPbA" target='blank'></a>
          <img src="/wp-content/themes/nure/images/icons/youtube.png" alt="">
        </div>

        <div class="footer__icon icon">
          <a class='footer__link' href="https://www.linkedin.com/in/ice-department-nure-0616b7239/" target='blank'></a>
          <img src="/wp-content/themes/nure/images/icons/linkedin-white.svg" alt="">
        </div>

        <div class="footer__icon icon">
          <a class='footer__link' href="https://www.tiktok.com/@ice_nure?_t=8hBdWF8aMRZ&_r=1" target='blank'></a>
          <img src="/wp-content/themes/nure/images/icons/tik-tok.png" alt="">
        </div>

      </div>

    </div>

    <div class="popup__close"></div>

    <div class="popup__images">

      <a class="popup__link" href="http://ice.nure.ua/<?= get_translation('ua', 'en'); ?>/about/specialties/125-bachelor/">
        <span class="desktop">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/f5.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/f5_en.jpg'); ?>" class="popup__image">
        </span>
        <span class="mobile">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/f5.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/f5_en.jpg'); ?>" class="popup__image">
        </span>
      </a>

      <a class="popup__link" href="https://ice.nure.ua/<?= get_translation('ua', 'en'); ?>/about/specialties/osvitnia-prohrama-informatsijni-systemy-ta-tekhnolohii-kvalifikatsijnyj-riven-bakalavr/">
        <span class="desktop">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/f6.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/f6_en.jpg'); ?>" class="popup__image">
        </span>
        <span class="mobile">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/f6.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/f6_en.jpg'); ?>" class="popup__image">
        </span>
      </a>

      <a class="popup__link" href="https://ice.nure.ua/<?= get_translation('ua', 'en'); ?>/about/specialties/172-bachelor-2">
        <span class="desktop">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/5-3.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/6-3.jpg'); ?>" class="popup__image">
        </span>
        <span class="mobile">
          <img src="<?= get_translation('https://ice.nure.ua/wp-content/uploads/2025/03/5-3.jpg', 'https://ice.nure.ua/wp-content/uploads/2025/03/6-3.jpg'); ?>" class="popup__image">
        </span>
      </a>

    </div>

    <div class="popup__title popup__title--potential">
      <a href="https://ice.nure.ua/<?= get_translation('ua', 'en'); ?>/learning-process/teaching-staff/">
        <?= get_translation('Потенціал кафедри', 'Department potential'); ?>
      </a>
    </div>

    <div class="popup__numbers">

      <div class="popup__number">

        <div class="popup__number-digit" data-max="11">
          0
        </div>

        <div class="popup__number-text">
          <?= get_translation('Професори', 'Professors'); ?>
        </div>

      </div>

      <div class="popup__number">

        <div class="popup__number-digit" data-max="10">
          0
        </div>

        <div class="popup__number-text">
          <?= get_translation('Доценти', 'Docents'); ?>
        </div>

      </div>

      <div class="popup__number">

        <div class="popup__number-digit" data-max="10">
          0
        </div>

        <div class="popup__number-text">
          <?= get_translation('Доктори наук', 'Doctors of Science'); ?>
        </div>

      </div>

      <div class="popup__number">

        <a href="https://ice.nure.ua/<?= get_translation('ua', 'en'); ?>/learning-process/training-laboratories/">

          <div class="popup__number-digit" data-max="11">
            0
          </div>

          <div class="popup__number-text">
            <?= get_translation('Сучасні лабораторії', 'Modern laboratories'); ?>
          </div>
 
        </a>  

      </div>

    </div>

  </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="<?=ROOT?>/js/min/gallery.js"></script>
<script src="<?=ROOT?>/js/min/slick-gallery.js?h=1.05"></script>
<script defer src="<?=ROOT?>/js/min/common.js?h=1.19"></script>

<style>
img[crossorigin="anonymous"]{
  display: none !important;
}
</style>

<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.27.7'><\/script>".replace("HOST", location.hostname));
//]]></script>

</body>
</html>