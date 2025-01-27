
<?php
if (!class_exists('TUTZ_Dashboard_Updater')) {
    class TUTZ_Dashboard_Updater {
        private $plugin_file;
        private $github_url;
        private $plugin_slug;

        public function __construct($plugin_file, $github_url) {
            $this->plugin_file = $plugin_file;
            $this->github_url = $github_url;
            $this->plugin_slug = plugin_basename($plugin_file);

            add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_updates']);
            add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
        }

        public function check_for_updates($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }

            $response = wp_remote_get("{$this->github_url}/releases/latest");
            if (is_wp_error($response)) {
                return $transient;
            }

            $data = json_decode(wp_remote_retrieve_body($response));
            if (version_compare($data->tag_name, TUTZ_DASHBOARD_VERSION, '>')) {
                $transient->response[$this->plugin_slug] = (object) [
                    'slug' => $this->plugin_slug,
                    'new_version' => $data->tag_name,
                    'url' => $this->github_url,
                    'package' => "{$this->github_url}/archive/refs/tags/{$data->tag_name}.zip"
                ];
            }

            return $transient;
        }

        public function plugin_info($result, $action, $args) {
            if ($action !== 'plugin_information' || $args->slug !== $this->plugin_slug) {
                return $result;
            }

            $response = wp_remote_get("{$this->github_url}/releases/latest");
            if (is_wp_error($response)) {
                return $result;
            }

            $data = json_decode(wp_remote_retrieve_body($response));
            $result = (object) [
                'name' => '[TUTZ] Dashboard',
                'slug' => $this->plugin_slug,
                'version' => $data->tag_name,
                'author' => '<a href="https://tutzagencia.com.br">TUTZ Agência</a>',
                'homepage' => $this->github_url,
                'download_link' => "{$this->github_url}/archive/refs/tags/{$data->tag_name}.zip",
                'requires' => '5.0',
                'tested' => '6.3',
                'sections' => [
                    'description' => 'Plugin oficial da TUTZ Agência com múltiplas funções administrativas.',
                ]
            ];

            return $result;
        }
    }
}

// Initialize the updater
if (is_admin()) {
    new TUTZ_Dashboard_Updater(__FILE__, 'https://github.com/TutzAg/tutz-dashboard');
}
?>
