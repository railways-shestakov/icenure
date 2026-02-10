<?php
/**
 * Sidebar Management — Custom WordPress Admin Page
 * Works without ACF Pro. Uses WordPress options API for data storage.
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

/* ───────────────────────────────────────────────
 * Admin page registration
 * ─────────────────────────────────────────────── */

add_action('admin_menu', 'register_sidebar_admin_page');

function register_sidebar_admin_page() {
    add_menu_page(
        'Налаштування бічних панелей',
        'Бічні панелі',
        'edit_posts',
        'sidebar-settings',
        'render_sidebar_settings_page',
        'dashicons-align-right',
        31
    );
}

add_action('admin_enqueue_scripts', 'sidebar_admin_enqueue');

function sidebar_admin_enqueue($hook) {
    if ( $hook !== 'toplevel_page_sidebar-settings' ) {
        return;
    }
    wp_enqueue_script('jquery-ui-sortable');
}

/* ───────────────────────────────────────────────
 * Save handler
 * ─────────────────────────────────────────────── */

add_action('admin_init', 'handle_sidebar_settings_save');

function handle_sidebar_settings_save() {
    if ( ! isset($_POST['sidebar_settings_nonce']) ) {
        return;
    }
    if ( ! wp_verify_nonce($_POST['sidebar_settings_nonce'], 'save_sidebar_settings') ) {
        wp_die('Помилка безпеки.');
    }
    if ( ! current_user_can('edit_posts') ) {
        return;
    }

    $sidebar_key = sanitize_key($_POST['sidebar_key']);
    $definitions = get_sidebar_definitions();
    if ( ! isset($definitions[$sidebar_key]) ) {
        return;
    }

    $links = array();
    if ( ! empty($_POST['links']) && is_array($_POST['links']) ) {
        foreach ( $_POST['links'] as $link_data ) {
            if ( empty($link_data['title_ua']) ) {
                continue;
            }
            $is_section = ! empty($link_data['is_section_header']) ? 1 : 0;
            $links[] = array(
                'url'               => $is_section ? '' : sanitize_text_field(wp_unslash($link_data['url'])),
                'title_ua'          => sanitize_text_field(wp_unslash($link_data['title_ua'])),
                'title_en'          => sanitize_text_field(wp_unslash($link_data['title_en'])),
                'svg_icon'          => wp_unslash(trim($link_data['svg_icon'])),
                'is_section_header' => $is_section,
            );
        }
    }

    update_option('icenure_sidebar_' . $sidebar_key . '_links', $links);

    wp_redirect(add_query_arg(array(
        'page'    => 'sidebar-settings',
        'tab'     => $sidebar_key,
        'updated' => '1',
    ), admin_url('admin.php')));
    exit;
}

/* ───────────────────────────────────────────────
 * Render settings page
 * ─────────────────────────────────────────────── */

function render_sidebar_settings_page() {
    $definitions = get_sidebar_definitions();
    $keys        = array_keys($definitions);
    $active_tab  = isset($_GET['tab']) && isset($definitions[$_GET['tab']])
        ? sanitize_key($_GET['tab'])
        : $keys[0];

    $links = get_sidebar_links($active_tab);
    if ( $links === null ) {
        $links = array();
    }
    ?>
    <div class="wrap">
        <h1>Налаштування бічних панелей</h1>

        <?php if ( isset($_GET['updated']) ): ?>
            <div class="notice notice-success is-dismissible"><p>Налаштування збережено.</p></div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper" style="margin-bottom:15px">
            <?php foreach ( $definitions as $key => $label ): ?>
                <a href="<?php echo esc_url(add_query_arg(array('page' => 'sidebar-settings', 'tab' => $key), admin_url('admin.php'))); ?>"
                   class="nav-tab <?php echo $key === $active_tab ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <p class="description" style="margin-bottom:12px">
            Якщо список порожній — використовуються стандартні посилання з коду.
            Додайте хоча б одне посилання, щоб замінити стандартні.
        </p>

        <form method="post" action="">
            <?php wp_nonce_field('save_sidebar_settings', 'sidebar_settings_nonce'); ?>
            <input type="hidden" name="sidebar_key" value="<?php echo esc_attr($active_tab); ?>">

            <div id="sidebar-links-container">
                <?php foreach ( $links as $i => $link ): ?>
                    <?php render_sidebar_link_row($i, $link); ?>
                <?php endforeach; ?>
            </div>

            <p>
                <button type="button" class="button" id="add-sidebar-link">+ Додати посилання</button>
            </p>

            <?php submit_button('Зберегти зміни'); ?>
        </form>
    </div>

    <template id="sidebar-link-template">
        <?php render_sidebar_link_row('__INDEX__', array(
            'url' => '', 'title_ua' => '', 'title_en' => '', 'svg_icon' => '', 'is_section_header' => 0,
        )); ?>
    </template>

    <style>
        .sidebar-link-item{background:#fff;border:1px solid #ccd0d4;padding:14px 16px;margin:8px 0;cursor:move}
        .sidebar-link-item.ui-sortable-helper{box-shadow:0 2px 8px rgba(0,0,0,.15)}
        .sidebar-link-item.ui-sortable-placeholder{visibility:visible!important;background:#f0f6fc;border:2px dashed #2271b1}
        .sidebar-link-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #eee}
        .sidebar-link-header .link-number{font-weight:600;color:#1d2327;font-size:14px}
        .sidebar-link-fields{display:grid;grid-template-columns:1fr 1fr;gap:10px 16px}
        .sidebar-link-fields .field{display:flex;flex-direction:column}
        .sidebar-link-fields .field.full-width{grid-column:1/-1}
        .sidebar-link-fields .field label{font-weight:600;margin-bottom:3px;font-size:13px}
        .sidebar-link-fields .field input[type="text"],
        .sidebar-link-fields .field textarea{width:100%}
        .section-header-check{display:flex;align-items:center;gap:6px;font-size:13px}
        .section-header-check input{margin:0}
    </style>

    <script>
    jQuery(function($){
        var idx = <?php echo count($links); ?>;

        /* Sortable */
        $('#sidebar-links-container').sortable({
            items: '.sidebar-link-item',
            handle: '.sidebar-link-header',
            placeholder: 'sidebar-link-item ui-sortable-placeholder',
            opacity: 0.8,
            tolerance: 'pointer',
            stop: function(){ updateNumbers(); }
        });

        /* Add row */
        $('#add-sidebar-link').on('click', function(){
            var html = $('#sidebar-link-template').html().replace(/__INDEX__/g, idx);
            $('#sidebar-links-container').append(html);
            idx++;
            updateNumbers();
            $('#sidebar-links-container').sortable('refresh');
        });

        /* Remove row */
        $(document).on('click', '.remove-sidebar-link', function(){
            $(this).closest('.sidebar-link-item').remove();
            updateNumbers();
        });

        /* Toggle URL/SVG fields for section headers */
        $(document).on('change', '.is-section-header-cb', function(){
            var item = $(this).closest('.sidebar-link-item');
            var hide = this.checked;
            item.find('.url-field-wrap, .svg-field-wrap').toggle(!hide);
        });

        function updateNumbers(){
            $('#sidebar-links-container .sidebar-link-item').each(function(i){
                $(this).find('.link-number').text('#' + (i + 1));
            });
        }
    });
    </script>
    <?php
}

/**
 * Render a single link row for the admin form.
 */
function render_sidebar_link_row($index, $link) {
    $is_section = ! empty($link['is_section_header']);
    $prefix = "links[{$index}]";
    ?>
    <div class="sidebar-link-item">
        <div class="sidebar-link-header">
            <span class="link-number">#<?php echo is_numeric($index) ? $index + 1 : ''; ?></span>
            <div style="display:flex;align-items:center;gap:14px">
                <label class="section-header-check">
                    <input type="checkbox" class="is-section-header-cb"
                           name="<?php echo $prefix; ?>[is_section_header]"
                           value="1" <?php checked($is_section); ?>>
                    Заголовок секції
                </label>
                <button type="button" class="button button-link-delete remove-sidebar-link">Видалити</button>
            </div>
        </div>
        <div class="sidebar-link-fields">
            <div class="field url-field-wrap" <?php if ($is_section) echo 'style="display:none"'; ?>>
                <label>URL</label>
                <input type="text" name="<?php echo $prefix; ?>[url]"
                       value="<?php echo esc_attr($link['url']); ?>"
                       placeholder="/url-address">
            </div>
            <div class="field">
                <label>Назва (UA) *</label>
                <input type="text" name="<?php echo $prefix; ?>[title_ua]"
                       value="<?php echo esc_attr($link['title_ua']); ?>">
            </div>
            <div class="field">
                <label>Назва (EN)</label>
                <input type="text" name="<?php echo $prefix; ?>[title_en]"
                       value="<?php echo esc_attr($link['title_en']); ?>">
            </div>
            <div class="field full-width svg-field-wrap" <?php if ($is_section) echo 'style="display:none"'; ?>>
                <label>SVG іконка</label>
                <textarea name="<?php echo $prefix; ?>[svg_icon]" rows="2"
                          placeholder="<svg>...</svg>"><?php echo esc_textarea($link['svg_icon']); ?></textarea>
            </div>
        </div>
    </div>
    <?php
}

/* ───────────────────────────────────────────────
 * Data access (used by sidebar-*.php templates)
 * ─────────────────────────────────────────────── */

/**
 * Get sidebar links from WordPress options.
 * Returns array of links or null if none configured.
 *
 * @param string $sidebar_key Sidebar identifier
 * @return array|null
 */
function get_sidebar_links($sidebar_key) {
    $links = get_option('icenure_sidebar_' . $sidebar_key . '_links');
    if ( empty($links) || ! is_array($links) ) {
        return null;
    }
    return $links;
}

/**
 * Render sidebar links as HTML.
 * Returns HTML string or empty string if not configured.
 *
 * @param string $sidebar_key Sidebar identifier
 * @return string
 */
function render_sidebar_links($sidebar_key) {
    $links = get_sidebar_links($sidebar_key);
    if ( $links === null ) {
        return '';
    }

    $output   = '';
    $in_block = false;

    foreach ( $links as $link ) {
        $is_section = ! empty($link['is_section_header']);

        if ( $is_section ) {
            if ( $in_block ) {
                $output  .= "    </div>\n";
                $in_block = false;
            }
            $title_ua = $link['title_ua'];
            $title_en = ! empty($link['title_en']) ? $link['title_en'] : $link['title_ua'];
            $output  .= "\n    <h3>\n";
            $output  .= "        " . get_translation($title_ua, $title_en) . "\n";
            $output  .= "    </h3>\n\n";
            continue;
        }

        if ( ! $in_block ) {
            $output  .= "    <div class=\"sidebar__block\">\n";
            $in_block = true;
        }

        $url      = esc_attr($link['url']);
        $title_ua = $link['title_ua'];
        $title_en = ! empty($link['title_en']) ? $link['title_en'] : $link['title_ua'];
        $svg      = ! empty($link['svg_icon']) ? $link['svg_icon'] : '';

        $output .= "        <a href=\"{$url}\">\n";
        if ( $svg ) {
            $output .= "            {$svg}\n";
        }
        $output .= "            " . get_translation($title_ua, $title_en) . "\n";
        $output .= "        </a>\n";
    }

    if ( $in_block ) {
        $output .= "    </div>\n";
    }

    return $output;
}

/* ───────────────────────────────────────────────
 * Parser & seeder (populates from hardcoded HTML)
 * ─────────────────────────────────────────────── */

/**
 * Parse a sidebar-{key}.php file and extract link data from hardcoded HTML.
 *
 * @param string $sidebar_key Sidebar identifier
 * @return array
 */
function parse_sidebar_file($sidebar_key) {
    $file = get_template_directory() . '/sidebar-' . $sidebar_key . '.php';
    if ( ! file_exists($file) ) {
        return array();
    }

    $content = file_get_contents($file);

    // Remove HTML comments
    $content = preg_replace('/<!--[\s\S]*?-->/', '', $content);

    // Extract fallback section (between else: and endif:)
    if ( preg_match('/else\s*:\s*\?>([\s\S]*?)<\?php\s+endif/i', $content, $fallback_match) ) {
        $html = $fallback_match[1];
    } else {
        $html = $content;
    }

    $items   = array();
    $pattern = '/(?:<h3>\s*<\?=\s*get_translation\(\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*\)\s*;?\s*\?>\s*<\/h3>)|(?:<a\s+href="([^"]+)">\s*((?:<svg[\s\S]*?<\/svg>)?\s*)<\?=\s*get_translation\(\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*\)\s*;?\s*\?>\s*<\/a>)/i';

    if ( preg_match_all($pattern, $html, $matches, PREG_SET_ORDER) ) {
        foreach ( $matches as $m ) {
            if ( ! empty($m[1]) ) {
                $items[] = array(
                    'url'               => '',
                    'title_ua'          => $m[1],
                    'title_en'          => $m[2],
                    'svg_icon'          => '',
                    'is_section_header' => 1,
                );
            } else {
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
 * Seed sidebar options with default items parsed from hardcoded HTML.
 * Runs once on admin_init.
 */
function seed_sidebar_defaults() {
    if ( get_option('icenure_sidebar_defaults_seeded') ) {
        return;
    }

    $sidebars = get_sidebar_definitions();

    foreach ( $sidebars as $key => $label ) {
        $existing = get_option('icenure_sidebar_' . $key . '_links');
        if ( ! empty($existing) ) {
            continue;
        }

        $items = parse_sidebar_file($key);
        if ( ! empty($items) ) {
            update_option('icenure_sidebar_' . $key . '_links', $items);
        }
    }

    update_option('icenure_sidebar_defaults_seeded', true);
}

add_action('admin_init', 'seed_sidebar_defaults');

/* ───────────────────────────────────────────────
 * Universal Page integration (works with ACF free)
 * ─────────────────────────────────────────────── */

add_filter('acf/load_field/key=field_5f099e61a6cf0', 'load_sidebar_choices');

function load_sidebar_choices($field) {
    $field['choices'] = get_sidebar_definitions();
    return $field;
}
