<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Proteção contra acesso direto
}

class TUTZ_Dashboard_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'init', [ $this, 'apply_saved_settings' ] );
        add_action( 'login_enqueue_scripts', [ $this, 'redirect_custom_login' ] );
    }

    public function register_menu() {
        add_menu_page(
            __( 'TUTZ Dashboard', 'tutz-dashboard' ),
            __( 'TUTZ Dashboard', 'tutz-dashboard' ),
            'manage_options',
            'tutz-dashboard',
            [ $this, 'render_admin_page' ],
            'dashicons-admin-generic',
            2
        );
    }

    public function enqueue_admin_assets( $hook ) {
        if ( $hook === 'toplevel_page_tutz-dashboard' ) {
            wp_enqueue_style(
                'tutz-dashboard-admin',
                TUTZ_DASHBOARD_PLUGIN_URL . 'admin/css/admin-styles.css',
                [],
                TUTZ_DASHBOARD_VERSION
            );
        }
    }

    public function register_settings() {
        register_setting(
            'tutz_dashboard_settings',
            'tutz_dashboard_settings',
            [ 'sanitize_callback' => [ $this, 'sanitize_options' ] ]
        );

        add_settings_section(
            'tutz_dashboard_general',
            __( 'Configurações Gerais', 'tutz-dashboard' ),
            '__return_false',
            'tutz-dashboard'
        );

        // Funções gerais
        $this->add_toggle_field(
            'enable_classic_editor',
            __( 'Ativar Editor Clássico', 'tutz-dashboard' ),
            __( 'Marque para ativar o Editor Clássico do WordPress.', 'tutz-dashboard' )
        );

        $this->add_toggle_field(
            'hide_admin_bar',
            __( 'Desativar Barra de Admin', 'tutz-dashboard' ),
            __( 'Marque para desativar a barra de admin no frontend.', 'tutz-dashboard' )
        );

        $this->add_toggle_field(
            'redirect_to_checkout',
            __( 'Redirecionar para Checkout', 'tutz-dashboard' ),
            __( 'Marque para redirecionar produtos diretamente para o checkout.', 'tutz-dashboard' )
        );

        $this->add_toggle_field(
            'clear_cart',
            __( 'Limpador de Carrinho', 'tutz-dashboard' ),
            __( 'Marque para limpar automaticamente o carrinho.', 'tutz-dashboard' )
        );

        // Funcionalidade de login personalizada
        $this->add_page_select_field(
            'custom_login_page',
            __( 'Página de Login Personalizada', 'tutz-dashboard' ),
            __( 'Selecione uma página criada no Elementor para substituir a página de login padrão.', 'tutz-dashboard' )
        );
    }

    public function add_toggle_field( $key, $label, $description ) {
        add_settings_field(
            $key,
            $label,
            [ $this, 'render_toggle' ],
            'tutz-dashboard',
            'tutz_dashboard_general',
            [
                'label_for'   => $key,
                'option_name' => 'tutz_dashboard_settings',
                'option_key'  => $key,
                'description' => $description,
            ]
        );
    }

    public function render_toggle( $args ) {
        $options = get_option( $args['option_name'], [] );
        $value   = isset( $options[ $args['option_key'] ] ) ? $options[ $args['option_key'] ] : false;
        ?>
        <label class="tutz-toggle">
            <input 
                type="checkbox" 
                id="<?php echo esc_attr( $args['label_for'] ); ?>" 
                name="<?php echo esc_attr( $args['option_name'] . '[' . $args['option_key'] . ']' ); ?>" 
                value="1" <?php checked( $value, 1 ); ?> />
            <span class="slider"></span>
        </label>
        <p class="description"><?php echo esc_html( $args['description'] ); ?></p>
        <?php
    }

    public function add_page_select_field( $key, $label, $description ) {
        add_settings_field(
            $key,
            $label,
            [ $this, 'render_page_select' ],
            'tutz-dashboard',
            'tutz_dashboard_general',
            [
                'label_for'   => $key,
                'option_name' => 'tutz_dashboard_settings',
                'option_key'  => $key,
                'description' => $description,
            ]
        );
    }

    public function render_page_select( $args ) {
        $options = get_option( $args['option_name'], [] );
        $selected = isset( $options[ $args['option_key'] ] ) ? $options[ $args['option_key'] ] : '';
        $pages = get_pages();
        ?>
        <select 
            id="<?php echo esc_attr( $args['label_for'] ); ?>" 
            name="<?php echo esc_attr( $args['option_name'] . '[' . $args['option_key'] . ']' ); ?>">
            <option value=""><?php esc_html_e( 'Selecione uma página...', 'tutz-dashboard' ); ?></option>
            <?php foreach ( $pages as $page ) : ?>
                <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $selected, $page->ID ); ?>>
                    <?php echo esc_html( $page->post_title ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php echo esc_html( $args['description'] ); ?></p>
        <?php
    }

    public function sanitize_options( $options ) {
        $sanitized = [];
        foreach ( $options as $key => $value ) {
            $sanitized[ $key ] = sanitize_text_field( $value );
        }
        return $sanitized;
    }

    public function apply_saved_settings() {
        $options = get_option( 'tutz_dashboard_settings', [] );

        if ( isset( $options['enable_classic_editor'] ) && $options['enable_classic_editor'] ) {
            add_filter( 'use_block_editor_for_post', '__return_false', 10 );
        }

        if ( isset( $options['hide_admin_bar'] ) && $options['hide_admin_bar'] ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }

        if ( isset( $options['redirect_to_checkout'] ) && $options['redirect_to_checkout'] ) {
            add_filter( 'woocommerce_add_to_cart_redirect', function() {
                return wc_get_checkout_url();
            } );
        }

        if ( isset( $options['clear_cart'] ) && $options['clear_cart'] ) {
            add_action( 'woocommerce_before_cart', function() {
                WC()->cart->empty_cart();
            } );
        }
    }

    public function redirect_custom_login() {
        $options = get_option( 'tutz_dashboard_settings', [] );
        if ( isset( $options['custom_login_page'] ) && ! empty( $options['custom_login_page'] ) ) {
            if ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ) {
                wp_redirect( get_permalink( $options['custom_login_page'] ) );
                exit();
            }
        }
    }

    public function render_admin_page() {
        ?>
        <div class="wrap tutz-dashboard-admin">
            <h1><?php esc_html_e( 'TUTZ Dashboard', 'tutz-dashboard' ); ?></h1>
            <?php settings_errors( 'tutz_dashboard_settings' ); ?>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'tutz_dashboard_settings' );
                do_settings_sections( 'tutz-dashboard' );
                submit_button( __( 'Salvar Alterações', 'tutz-dashboard' ) );
                ?>
            </form>
        </div>
        <?php
    }
}
