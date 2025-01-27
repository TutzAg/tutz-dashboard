<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Limpa o carrinho após cada requisição
function tutz_dashboard_clear_cart() {
    if ( get_option( 'tutz_clear_cart', false ) && class_exists( 'WC_Cart' ) ) {
        WC()->cart->empty_cart();
    }
}
add_action( 'woocommerce_before_cart', 'tutz_dashboard_clear_cart' );

// Adiciona a configuração no painel administrativo
function tutz_dashboard_register_clear_cart_setting() {
    register_setting( 'tutz_dashboard_settings', 'tutz_clear_cart' );

    add_settings_field(
        'tutz_clear_cart',
        __( 'Limpador de Carrinho', 'tutz-dashboard' ),
        'tutz_dashboard_render_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general',
        [
            'label_for' => 'tutz_clear_cart',
            'description' => __( 'Marque para limpar o carrinho automaticamente ao carregar a página do carrinho.', 'tutz-dashboard' ),
        ]
    );
}
add_action( 'admin_init', 'tutz_dashboard_register_clear_cart_setting' );
