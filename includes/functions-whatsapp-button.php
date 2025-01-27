
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Adds a WhatsApp button with a customizable phone number
function tutz_dashboard_add_whatsapp_button($content) {
    $phone_number = get_option('tutz_whatsapp_phone', '');
    if (!empty($phone_number)) {
        $button = '<div class="tutz-whatsapp-button"><a href="https://wa.me/' . esc_attr($phone_number) . '" target="_blank">WhatsApp</a></div>';
        return $content . $button;
    }
    return $content;
}
add_filter('the_content', 'tutz_dashboard_add_whatsapp_button');

// Register WhatsApp option in admin panel
function tutz_dashboard_register_whatsapp_setting() {
    register_setting('tutz_dashboard_settings', 'tutz_whatsapp_phone');
    add_settings_field(
        'tutz_whatsapp_phone',
        __('WhatsApp Number', 'tutz-dashboard'),
        'tutz_dashboard_render_whatsapp_input',
        'tutz-dashboard',
        'tutz_dashboard_general'
    );
}
add_action('admin_init', 'tutz_dashboard_register_whatsapp_setting');

function tutz_dashboard_render_whatsapp_input() {
    $value = get_option('tutz_whatsapp_phone', '');
    echo '<input type="text" id="tutz_whatsapp_phone" name="tutz_whatsapp_phone" value="' . esc_attr($value) . '" placeholder="5511999999999"/>';
}
?>
