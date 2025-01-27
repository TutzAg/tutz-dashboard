<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Redireciona para o checkout após adicionar ao carrinho
function tutz_dashboard_redirect_to_checkout() {
    if ( get_option( 'tutz_redirect_to_checkout', false ) ) {
        add_filter( 'woocommerce_add_to_cart_redirect', 'tutz_dashboard_wc_redirect_to_checkout' );
    }
}

function tutz_dashboard_wc_redirect_to_checkout() {
    return wc_get_checkout_url();
}
add_action( 'init', 'tutz_dashboard_redirect_to_checkout' );

// Adiciona a configuração no painel administrativo
function tutz_dashboard_register_redirect_to_checkout_setting() {
    register_setting( 'tutz_dashboard_settings', 'tutz_redirect_to_checkout' );

    add_settings_field(
        'tutz_redirect_to_checkout',
        __( 'Redirecionar para o Checkout', 'tutz-dashboard' ),
        'tutz_dashboard_render_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general',
        [
            'label_for' => 'tutz_redirect_to_checkout',
            'description' => __( 'Marque para redirecionar o produto diretamente para o checkout ao adicioná-lo ao carrinho. Utilize o formato de link: http://seusite.com/checkout/?add-to-cart=ID_DO_PRODUTO', 'tutz-dashboard' ),
        ]
    );
}
add_action( 'admin_init', 'tutz_dashboard_register_redirect_to_checkout_setting' );
