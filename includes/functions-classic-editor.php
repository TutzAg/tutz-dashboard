<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tutz_dashboard_enable_classic_editor() {
    if ( get_option( 'tutz_enable_classic_editor', false ) ) {
        add_filter( 'use_block_editor_for_post', '__return_false', 10 );
    }
}
add_action( 'init', 'tutz_dashboard_enable_classic_editor' );

// Adiciona a configuração no admin
function tutz_dashboard_register_classic_editor_setting() {
    register_setting( 'tutz_dashboard_settings', 'tutz_enable_classic_editor' );

    add_settings_section(
        'tutz_dashboard_general',
        __( 'Configurações Gerais', 'tutz-dashboard' ),
        '__return_false',
        'tutz-dashboard'
    );

    add_settings_field(
        'tutz_enable_classic_editor',
        __( 'Ativar Editor Clássico', 'tutz-dashboard' ),
        'tutz_dashboard_render_checkbox',
        'tutz-dashboard',
        'tutz_dashboard_general',
        [
            'label_for' => 'tutz_enable_classic_editor',
            'description' => __( 'Marque para ativar o Editor Clássico do WordPress.', 'tutz-dashboard' ),
        ]
    );
}
add_action( 'admin_init', 'tutz_dashboard_register_classic_editor_setting' );

function tutz_dashboard_render_checkbox( $args ) {
    $value = get_option( $args['label_for'], false );
    ?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php checked( $value, 1 ); ?> />
    <p class="description"><?php echo esc_html( $args['description'] ); ?></p>
    <?php
}
