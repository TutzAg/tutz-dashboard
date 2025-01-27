<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Remove a barra de admin no frontend se a opção estiver ativada
function tutz_dashboard_hide_admin_bar() {
    if ( get_option( 'tutz_hide_admin_bar', false ) ) {
        add_filter( 'show_admin_bar', '__return_false' );
    }
}
add_action( 'after_setup_theme', 'tutz_dashboard_hide_admin_bar' );

// Adiciona a configuração no painel administrativo
function tutz_dashboard_register_hide_admin_bar_setting() {
    register_setting( 'tutz_dashboard_settings', 'tutz_hide_admin_bar' );

    add_settings_field(
        'tutz_hide_admin_bar',
        __( 'Desativar Barra de Admin', 'tutz-dashboard' ),
        'tutz_dashboard_render_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general',
        [
            'label_for' => 'tutz_hide_admin_bar',
            'description' => __( 'Marque para desativar a barra de admin no frontend.', 'tutz-dashboard' ),
        ]
    );
}
add_action( 'admin_init', 'tutz_dashboard_register_hide_admin_bar_setting' );
