
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Minify JS content
function tutz_dashboard_minify_js($js) {
    return preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n", "\t"], '', $js));
}

// Register admin settings for minify JS
function tutz_dashboard_register_minify_js_setting() {
    register_setting('tutz_dashboard_settings', 'tutz_minify_js');
    add_settings_field(
        'tutz_minify_js',
        __('Enable JS Minification', 'tutz-dashboard'),
        'tutz_dashboard_render_minify_js_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general'
    );
}
add_action('admin_init', 'tutz_dashboard_register_minify_js_setting');

function tutz_dashboard_render_minify_js_checkbox() {
    $value = get_option('tutz_minify_js', false);
    echo '<input type="checkbox" id="tutz_minify_js" name="tutz_minify_js" value="1" ' . checked(1, $value, false) . '/>';
}
?>
