<?php
/**
 * ACF Options Page and Field Groups for Sidebar Management
 */

/**
 * Get sidebar definitions (key => label).
 * Single source of truth for all sidebar types.
 *
 * @return array Associative array of sidebar_key => label
 */
function get_sidebar_definitions() {
    return array(
        'students' => 'Студентська робота',
        'about'    => 'Про кафедру',
        'learning' => 'Освітній процес',
        'science'  => 'Наукова робота',
        'news'     => 'Новини',
        'entrants' => 'Абітурієнту',
    );
}

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

/**
 * Dynamically populate the sidebar selector in "Універсальна сторінка" template.
 * Uses key => label format so ACF stores the key (e.g. 'about') instead of the label.
 */
add_filter('acf/load_field/key=field_5f099e61a6cf0', 'load_sidebar_choices');

function load_sidebar_choices($field) {
    $field['choices'] = get_sidebar_definitions();
    return $field;
}

function register_sidebar_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }

    $sidebar_definitions = get_sidebar_definitions();
    $sidebars = array();
    foreach ($sidebar_definitions as $key => $label) {
        $sidebars[] = array('key' => $key, 'label' => $label);
    }

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
                    'required'     => 0,
                    'placeholder'  => '/url-address',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_sidebar_' . $sidebar['key'] . '_link_is_section',
                                'operator' => '!=',
                                'value' => '1',
                            ),
                        ),
                    ),
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

/**
 * Parse a sidebar-{key}.php file and extract link data from hardcoded HTML.
 *
 * @param string $sidebar_key Sidebar identifier
 * @return array Array of link items with url, title_ua, title_en, svg_icon, is_section_header
 */
function parse_sidebar_file($sidebar_key) {
    $file = get_template_directory() . '/sidebar-' . $sidebar_key . '.php';
    if ( ! file_exists($file) ) {
        return array();
    }

    $content = file_get_contents($file);

    // Remove HTML comments (skip commented-out links like calculator)
    $content = preg_replace('/<!--[\s\S]*?-->/', '', $content);

    // Extract fallback section (between else: and endif:)
    if ( preg_match('/else\s*:\s*\?>([\s\S]*?)<\?php\s+endif/i', $content, $fallback_match) ) {
        $html = $fallback_match[1];
    } else {
        $html = $content;
    }

    $items = array();

    // Combined regex: match h3 section headers OR a links with SVG and get_translation
    $pattern = '/(?:<h3>\s*<\?=\s*get_translation\(\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*\)\s*;?\s*\?>\s*<\/h3>)|(?:<a\s+href="([^"]+)">\s*((?:<svg[\s\S]*?<\/svg>)?\s*)<\?=\s*get_translation\(\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*\)\s*;?\s*\?>\s*<\/a>)/i';

    if ( preg_match_all($pattern, $html, $matches, PREG_SET_ORDER) ) {
        foreach ($matches as $m) {
            if ( ! empty($m[1]) ) {
                // h3 section header
                $items[] = array(
                    'url'               => '',
                    'title_ua'          => $m[1],
                    'title_en'          => $m[2],
                    'svg_icon'          => '',
                    'is_section_header' => 1,
                );
            } else {
                // a link
                $items[] = array(
                    'url'               => $m[3],
                    'title_ua'          => $m[5],
                    'title_en'          => $m[6],
                    'svg_icon'          => trim($m[4]),
                    'is_section_header' => 0,
                );
            }
        }
    }

    return $items;
}

/**
 * Seed sidebar ACF fields with default items parsed from hardcoded HTML.
 * Runs once on admin_init, populates empty repeaters with existing sidebar data.
 */
function seed_sidebar_defaults() {
    if ( get_option('icenure_sidebar_defaults_seeded') ) {
        return;
    }
    if ( ! function_exists('update_field') ) {
        return;
    }

    $sidebars = get_sidebar_definitions();

    foreach ($sidebars as $key => $label) {
        $existing = get_field('sidebar_' . $key . '_links', 'option');
        if ( ! empty($existing) ) {
            continue;
        }

        $items = parse_sidebar_file($key);
        if ( ! empty($items) ) {
            update_field('field_sidebar_' . $key . '_links', $items, 'option');
        }
    }

    update_option('icenure_sidebar_defaults_seeded', true);
}

add_action('admin_init', 'seed_sidebar_defaults');
?>
