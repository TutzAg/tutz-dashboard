
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register MKT settings for the WhatsApp button
function tutz_dashboard_register_mkt_settings() {
    register_setting('tutz_dashboard_mkt', 'tutz_mkt_wapp_number');
    add_settings_field(
        'tutz_mkt_wapp_number',
        __('Número WhatsApp', 'tutz-dashboard'),
        'tutz_dashboard_render_mkt_wapp_input',
        'tutz-dashboard',
        'tutz_dashboard_mkt'
    );
}
add_action('admin_init', 'tutz_dashboard_register_mkt_settings');

function tutz_dashboard_render_mkt_wapp_input() {
    $value = get_option('tutz_mkt_wapp_number', '');
    echo '<input type="text" id="tutz_mkt_wapp_number" name="tutz_mkt_wapp_number" value="' . esc_attr($value) . '" placeholder="5511999999999"/>';
}

// Add WhatsApp button to content
function tutz_dashboard_add_mkt_wapp_button($content) {
    $number = get_option('tutz_mkt_wapp_number', '');
    if (!empty($number)) {
        $button = '<div class="tutz-mkt-wapp-button"><a href="https://wa.me/' . esc_attr($number) . '" target="_blank">Fale pelo WhatsApp</a></div>';
        return $content . $button;
    }
    return $content;
}
add_filter('the_content', 'tutz_dashboard_add_mkt_wapp_button');
?>
