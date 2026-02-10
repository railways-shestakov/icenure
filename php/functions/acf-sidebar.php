<?php
/**
 * ACF Options Page and Field Groups for Sidebar Management
 */

if ( ! function_exists('acf_add_options_page') ) {
    return;
}

acf_add_options_page(array(
    'page_title' => 'Налаштування бічних панелей',
    'menu_title' => 'Бічні панелі',
    'menu_slug'  => 'sidebar-settings',
    'capability' => 'edit_posts',
    'icon_url'   => 'dashicons-align-right',
    'position'   => 31,
));

add_action('acf/init', 'register_sidebar_fields');

function register_sidebar_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }

    $sidebars = array(
        array(
            'key'   => 'students',
            'label' => 'Студентська робота',
        ),
        array(
            'key'   => 'about',
            'label' => 'Про кафедру',
        ),
        array(
            'key'   => 'learning',
            'label' => 'Освітній процес',
        ),
        array(
            'key'   => 'science',
            'label' => 'Наукова робота',
        ),
        array(
            'key'   => 'news',
            'label' => 'Новини',
        ),
        array(
            'key'   => 'entrants',
            'label' => 'Абітурієнту',
        ),
    );

    $fields = array();

    foreach ($sidebars as $sidebar) {
        $fields[] = array(
            'key'   => 'field_sidebar_' . $sidebar['key'] . '_tab',
            'label' => $sidebar['label'],
            'name'  => '',
            'type'  => 'tab',
            'placement' => 'left',
        );

        $fields[] = array(
            'key'          => 'field_sidebar_' . $sidebar['key'] . '_links',
            'label'        => 'Посилання — ' . $sidebar['label'],
            'name'         => 'sidebar_' . $sidebar['key'] . '_links',
            'type'         => 'repeater',
            'instructions' => 'Якщо порожньо — використовуються стандартні посилання з коду. Додайте хоча б одне посилання, щоб замінити стандартні.',
            'required'     => 0,
            'min'          => 0,
            'max'          => 0,
            'layout'       => 'block',
            'button_label' => 'Додати посилання',
            'sub_fields'   => array(
                array(
                    'key'          => 'field_sidebar_' . $sidebar['key'] . '_link_url',
                    'label'        => 'URL',
                    'name'         => 'url',
                    'type'         => 'text',
                    'instructions' => 'Наприклад: /educational-and-scientific-achievements',
                    'required'     => 1,
                    'placeholder'  => '/url-address',
                ),
                array(
                    'key'          => 'field_sidebar_' . $sidebar['key'] . '_link_title_ua',
                    'label'        => 'Назва (UA)',
                    'name'         => 'title_ua',
                    'type'         => 'text',
                    'required'     => 1,
                ),
                array(
                    'key'          => 'field_sidebar_' . $sidebar['key'] . '_link_title_en',
                    'label'        => 'Назва (EN)',
                    'name'         => 'title_en',
                    'type'         => 'text',
                    'required'     => 0,
                ),
                array(
                    'key'          => 'field_sidebar_' . $sidebar['key'] . '_link_svg',
                    'label'        => 'SVG іконка',
                    'name'         => 'svg_icon',
                    'type'         => 'textarea',
                    'instructions' => 'Вставте SVG код іконки (тег &lt;svg&gt;...&lt;/svg&gt;)',
                    'required'     => 0,
                    'rows'         => 3,
                    'new_lines'    => '',
                ),
                array(
                    'key'          => 'field_sidebar_' . $sidebar['key'] . '_link_is_section',
                    'label'        => 'Це заголовок секції?',
                    'name'         => 'is_section_header',
                    'type'         => 'true_false',
                    'instructions' => 'Увімкніть для розділового заголовку (тег h3), який починає нову групу посилань',
                    'default_value' => 0,
                    'ui'           => 1,
                ),
            ),
        );
    }

    acf_add_local_field_group(array(
        'key'                  => 'group_sidebar_settings',
        'title'                => 'Бічні панелі',
        'fields'               => $fields,
        'location'             => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'sidebar-settings',
                ),
            ),
        ),
        'style'                => 'default',
        'label_placement'      => 'top',
        'instruction_placement' => 'label',
        'active'               => true,
    ));
}

/**
 * Get sidebar links from ACF options.
 * Returns array of links or null if none configured.
 *
 * @param string $sidebar_key Sidebar identifier (students, about, learning, science, news, entrants)
 * @return array|null Array of link data or null if not configured
 */
function get_sidebar_links($sidebar_key) {
    if ( ! function_exists('get_field') ) {
        return null;
    }

    $links = get_field('sidebar_' . $sidebar_key . '_links', 'option');

    if ( empty($links) ) {
        return null;
    }

    return $links;
}

/**
 * Render sidebar links from ACF.
 * Returns HTML string of sidebar content, or empty string if not configured.
 *
 * @param string $sidebar_key Sidebar identifier
 * @return string HTML output
 */
function render_sidebar_links($sidebar_key) {
    $links = get_sidebar_links($sidebar_key);

    if ( $links === null ) {
        return '';
    }

    $output = '';
    $in_block = false;

    foreach ($links as $index => $link) {
        $is_section = !empty($link['is_section_header']);

        if ($is_section) {
            if ($in_block) {
                $output .= "    </div>\n";
                $in_block = false;
            }
            $title_ua = $link['title_ua'];
            $title_en = !empty($link['title_en']) ? $link['title_en'] : $link['title_ua'];
            $output .= "\n    <h3>\n";
            $output .= "        " . get_translation($title_ua, $title_en) . "\n";
            $output .= "    </h3>\n\n";
            continue;
        }

        if (!$in_block) {
            $output .= "    <div class=\"sidebar__block\">\n";
            $in_block = true;
        }

        $url = esc_attr($link['url']);
        $title_ua = $link['title_ua'];
        $title_en = !empty($link['title_en']) ? $link['title_en'] : $link['title_ua'];
        $svg = !empty($link['svg_icon']) ? $link['svg_icon'] : '';

        $output .= "        <a href=\"{$url}\">\n";
        if ($svg) {
            $output .= "            {$svg}\n";
        }
        $output .= "            " . get_translation($title_ua, $title_en) . "\n";
        $output .= "        </a>\n";
    }

    if ($in_block) {
        $output .= "    </div>\n";
    }

    return $output;
}
?>
