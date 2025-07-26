<?php
/* ValidateCertify Free Admin Dashboard
 *
 * @class    stvc-admin-dashboard
 * @package  ValidateCertify
 */
class STVC_Admin_Dashboard {
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_custom_dashboard_widget'));
        add_action('admin_enqueue_scripts', array($this, 'load_custom_styles'));
    }

    public function add_custom_dashboard_widget() {
        // Registra la caja personalizada en el dashboard
        wp_add_dashboard_widget(
            'custom_dashboard_widget_stvc', // ID único de la caja personalizada
            __('ValidateCertify Free', 'stvc_validatecertify') . ' ' . stvc_validatecertify_version, 
            array($this, 'render_custom_dashboard_widget_stvc')
        );
    }
    // Función para cargar estilos personalizados
    public function load_custom_styles() {
        // Carga el archivo CSS desde la ubicación de tu plugin
        wp_enqueue_style('stvc-custom-styles', plugins_url('assets/css/validatecertify-styles.css', __FILE__));
    }
    
    // Función para renderizar el contenido de la caja personalizada
    public function render_custom_dashboard_widget_stvc() {
        ?>
        <h><?php esc_html_e('Access your tools', 'stvc_validatecertify'); ?></h><!-- Accede a tus herramientas -->
        <hr>
        <div class="custom-dashboard-widget-stvc">
            <p><?php esc_attr_e('Add a certificate.', 'stvc_validatecertify'); ?></p><!-- Añadir un certificado -->
            <a href="<?php echo esc_url(admin_url('admin.php?page=new_certificates_stvc')); ?>" class="button button-primary"><?php echo esc_html__('Add Certificate', 'stvc_validatecertify'); ?></a>
        </div>
        <div class="custom-dashboard-widget-stvc">
            <p><?php esc_attr_e('Review ShortCorde.', 'stvc_validatecertify'); ?></p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=tools_validatecertify')); ?>" class="button button-primary"><?php echo esc_html__('Tools', 'stvc_validatecertify'); ?></a>
        </div>
        <?php
    }
    
}