
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Minify CSS content
function tutz_dashboard_minify_css($css) {
    return preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n", "\t"], '', $css));
}

// Register admin settings for minify CSS
function tutz_dashboard_register_minify_css_setting() {
    register_setting('tutz_dashboard_settings', 'tutz_minify_css');
    add_settings_field(
        'tutz_minify_css',
        __('Enable CSS Minification', 'tutz-dashboard'),
        'tutz_dashboard_render_minify_css_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general'
    );
}
add_action('admin_init', 'tutz_dashboard_register_minify_css_setting');

function tutz_dashboard_render_minify_css_checkbox() {
    $value = get_option('tutz_minify_css', false);
    echo '<input type="checkbox" id="tutz_minify_css" name="tutz_minify_css" value="1" ' . checked(1, $value, false) . '/>';
}
?>
