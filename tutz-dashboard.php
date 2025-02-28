<?php
/**
 * Plugin Name: [TUTZ] Dashboard
 * Plugin URI: https://github.com/TutzAg/tutz-dashboard
 * Description: Plugin oficial da TUTZ Agência com múltiplas funções administrativas.
 * Version: 1.1
 * Author: TUTZ Agência
 * Author URI: https://tutzagencia.com.br
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Proteção contra acesso direto
}

// Define constantes
define( 'TUTZ_DASHBOARD_VERSION', '1.1' );
define( 'TUTZ_DASHBOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TUTZ_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Requer arquivos necessários
require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'admin/class-tutz-dashboard-admin.php';

// Inicializa o painel administrativo
function tutz_dashboard_init() {
    new TUTZ_Dashboard_Admin();
}
add_action( 'plugins_loaded', 'tutz_dashboard_init' );

// Função de ativação
function tutz_dashboard_activate() {
    if ( ! get_option( 'tutz_dashboard_settings' ) ) {
        add_option( 'tutz_dashboard_settings', [] );
    }
}
register_activation_hook( __FILE__, 'tutz_dashboard_activate' );

// Função de desativação
function tutz_dashboard_deactivate() {
    // Aqui você pode remover opções ou executar ações ao desativar
}
register_deactivation_hook( __FILE__, 'tutz_dashboard_deactivate' );


// Include new features
require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/functions-whatsapp-button.php';
require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/functions-minify-css.php';
require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/functions-minify-js.php';



if (is_admin() && file_exists(TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/github-updater.php')) {
    require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/github-updater.php';
    new TUTZ_Dashboard_Updater(__FILE__, 'https://github.com/TutzAg/tutz-dashboard');
}



// Include MKT category functions
require_once TUTZ_DASHBOARD_PLUGIN_DIR . 'includes/functions-mkt.php';
