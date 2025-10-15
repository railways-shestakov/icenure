<?php
  /*
    Template Name: Розвиток навчальних дисциплін - Дубль
  */

  // GLOBAL VARIABLES
  
  include_once 'variables.php';

  // LOCAL VARIABLES
  
  $args = array(
    'numberposts'      => POSTS_NUMBER,
    'post_type'        => 'post',
    'meta_query' => array(
      array(
        'key'     => 'category',
        'value'   => '"Розвиток навчальних дисциплін"',
        'compare' => 'LIKE'
      )
    ),
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'meta_key'  => 'date',
    'orderby'   => 'meta_value_num',
    'order'     => 'DESC',
  );
  
  $context = create_context($args);
  $context['pagetitle'] = get_translation('Розвиток освітніх (освітньо-професійних та освітньо-наукових) програм', 'Development of educational (educational and professional, educational-scientific) programs');
  $template = DEVICE . '/news.twig';

  // HEADER

  $_POST['canonical'] = "http://ice.nure.ua/ua/development-of-educational-programs/";

  get_header();

  if(LANGUAGE != "en") $context['custom_html'] = <<<EOD
<div class="main__block main__page" style="margin: 0 0 20px;">
  <p>
    Розвиток освітніх програм є важливим фактором вдосконалення освітнього процесу та підвищення якості освіти та рівня підготовки студентів та аспірантів. Розвиток подібних програм базується на висновках щодо
  </p>

  <ul>
    <li>аналізу змін стандартів відповідних спеціальностей; </li>
    <li>зустрічей та опитувань стейкхолдерів, в т.ч. на основі їх постійного залучення до спільних обговорень змісту програм та до проведення підсумкової атестації здобувачів; </li>
    <li>підсумків екзаменаційних сесій, проходження практик та стажувань, захисту кваліфікаційних робіт;</li>
    <li>результатів студентських предметних олімпіад, конкурсів наукових робіт та проєктів, науково-технічних розробок та виставок;</li>
    <li>опитувань та заслуховувань студентів та аспірантів; </li>
    <li>результатів виконання програм міжнародного обміну, подвійного дипломування та спільних наукових досліджень;</li>
    <li>спільного обговорення змісту програм з представниками студентського самоврядування та Ради молодих вчених університету;</li>
    <li>науково-практичних та методичних семінарів (вебінарів), конференцій та форумів із залученням зацікавлених осіб; </li>
    <li>виконання освітніх та науково-дослідних робіт і проєктів (в т.ч. міжнародних);</li>
    <li>актів впровадження результатів наукових досліджень здобувачів наукових ступенів;</li>
    <li>аналізу рецензій на наукові публікації магістрантів та аспірантів у вітчизняних та іноземних часописах; </li>
    <li>аналізу відгуків на автореферати та дисертаційні роботи здобувачів наукових ступенів, які надходять від зацікавлених осіб та організацій (ЗВО, науково-дослідних та проєктних установ, відомих ІКТ-компаній та фірм).</li>
  </ul>
</div>
EOD;
  else $context['custom_html'] = <<<EOD
  <div class="main__block main__page" style="margin: 0 0 20px;">
    <p>
      The development of educational programs is an important factor in improving the educational process and raising the quality of education and the level of training of undergraduate and postgraduate students. The development of such programs is based on conclusions regarding 
    </p>
  
    <ul>
      <li>the analysis of changes in the standards of the respective professions;</li>
      <li>stakeholder meetings and surveys, including their regular involvement in joint discussions of program content and in the final assessment of applicants;</li>
      <li>the results of examination sessions, internships and traineeships and qualification papers;</li>
      <li>the results of student subject olympiads, research and project competitions, scientific and technical developments and exhibitions;</li>
      <li>surveys and hearings of undergraduate and postgraduate students;</li>
      <li>the results of international exchange, double degree and joint research programs;</li>
      <li>joint discussions of program content with representatives of the student government and the University Council of Young Scientists;</li>
      <li>scientific and methodological seminars (webinars), conferences and forums with the involvement of stakeholders;</li>
      <li> the implementation of educational and research works and projects (including international ones)</li>
      <li>the acts of implementation of the results of research of PhD candidates;</li>
      <li>the analysis of peer reviews of undergraduate and postgraduate research publications in domestic and foreign journals;</li>
      <li>the analysis of feedback on abstracts and dissertations of PhD candidates from interested individuals and organizations (universities, research and design institutions, renowned ICT companies and firms).</li> 
    </ul>
  </div>
  EOD;
?>

<main class='main main-sidebar'>

  <?php
    // PAGE CONTENT
  
    Timber::render($template, $context);

    // SIDEBAR

    if(!IS_MOBILE)
      get_sidebar('learning');
  ?>
</main>

<?php
  // FOOTER

  get_footer();
?>