<?php
/**
 * ACF Options Page and Field Group for Main Slider
 */

add_action('acf/init', 'register_slider_fields');

/**
 * Register the slider admin page.
 * Falls back to WordPress core add_menu_page() if ACF Pro is not available.
 */
add_action('admin_menu', 'register_slider_admin_page', 99);

function register_slider_admin_page() {
    global $admin_page_hooks;
    if ( isset($admin_page_hooks['slider-settings']) ) {
        return;
    }

    add_menu_page(
        'Налаштування слайдера',
        'Слайдер',
        'edit_posts',
        'slider-settings',
        'render_slider_settings_page',
        'dashicons-images-alt2',
        30
    );
}

function render_slider_settings_page() {
    echo '<div class="wrap">';
    echo '<h1>Налаштування слайдера</h1>';

    if ( ! function_exists('acf_add_options_page') ) {
        echo '<div class="notice notice-warning"><p>';
        echo 'Для повної функціональності цієї сторінки необхідний плагін <strong>Advanced Custom Fields PRO</strong>.';
        echo '</p></div>';
    }

    echo '</div>';
}

function register_slider_fields() {
    if ( ! function_exists('acf_add_local_field_group') ) {
        return;
    }

    if ( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title' => 'Налаштування слайдера',
            'menu_title' => 'Слайдер',
            'menu_slug'  => 'slider-settings',
            'capability' => 'edit_posts',
            'icon_url'   => 'dashicons-images-alt2',
            'position'   => 30,
        ));
    }

    acf_add_local_field_group(array(
        'key' => 'group_slider_settings',
        'title' => 'Слайди',
        'fields' => array(
            array(
                'key' => 'field_slider_slides',
                'label' => 'Слайди',
                'name' => 'slider_slides',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Додати слайд',
                'sub_fields' => array(
                    array(
                        'key' => 'field_slider_slide_image_ua',
                        'label' => 'Зображення (UA)',
                        'name' => 'image_ua',
                        'type' => 'image',
                        'instructions' => 'Зображення для української версії',
                        'required' => 1,
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_slider_slide_image_en',
                        'label' => 'Зображення (EN)',
                        'name' => 'image_en',
                        'type' => 'image',
                        'instructions' => 'Зображення для англійської версії (якщо не задано, використовується UA)',
                        'required' => 0,
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_slider_slide_image_style',
                        'label' => 'Стиль зображення',
                        'name' => 'image_style',
                        'type' => 'text',
                        'instructions' => 'Додатковий CSS для зображення, наприклад: max-height: 226px;',
                        'required' => 0,
                        'default_value' => '',
                        'placeholder' => 'max-height: 226px;',
                    ),
                    array(
                        'key' => 'field_slider_slide_image_wrapper_style',
                        'label' => 'Стиль обгортки зображення',
                        'name' => 'image_wrapper_style',
                        'type' => 'text',
                        'instructions' => 'Додатковий CSS для обгортки, наприклад: width: auto;',
                        'required' => 0,
                        'default_value' => '',
                        'placeholder' => 'width: auto;',
                    ),
                    array(
                        'key' => 'field_slider_slide_text_ua',
                        'label' => 'Текст (UA)',
                        'name' => 'text_ua',
                        'type' => 'wysiwyg',
                        'instructions' => 'Текст слайда українською мовою',
                        'required' => 1,
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                    array(
                        'key' => 'field_slider_slide_text_en',
                        'label' => 'Текст (EN)',
                        'name' => 'text_en',
                        'type' => 'wysiwyg',
                        'instructions' => 'Текст слайда англійською мовою (якщо не задано, використовується UA)',
                        'required' => 0,
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 0,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'slider-settings',
                ),
            ),
        ),
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
}
?>
