<?php
/* ValidateCertify Free Dashboard
 *
 * @class    stvc-admin-dashboard
 * @package  ValidateCertify
 */
function stvc_admin_notice_plugin_activation_hook() {
    set_transient('stvc-admin-notice-plugin', true, 5);
}

function stvc_admin_notice_plugin_notice() {
    if (get_transient('stvc-admin-notice-plugin')) {
        ?>
        <div class="notice notice-info is-dismissible">
            <div style="display: flex; align-items: center;">
                <img src="<?php echo esc_url( plugins_url( 'assets/img/ValidateCertify.png', dirname( __FILE__ ) ) ); ?>" alt="ValidateCertify" style="margin-right: 10px;">
                <div>
                    <h2><?php echo esc_html__( 'Thank you for installing ValidateCertify Free!', 'stvc_validatecertify' ); ?></h2>
                    <p><?php echo esc_html__( 'The new version is packed with enhancements designed to enrich your experience. We’d love to hear your thoughts on the updates we’ve made with you in mind. Help us continue improving for you and everyone else by leaving a review. Your feedback makes all the difference!', 'stvc_validatecertify' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=new_certificates_stvc' )  ); ?>" class="button button-primary" style="margin-right: 8px; padding: 5px 20px;"><?php echo esc_html__( 'Add Certificate', 'stvc_validatecertify' ); ?></a>
                        <a href="#" class="button button-primary" data-type="later" data-repeat-notice-after="2592000" style="margin-right: 8px; padding: 5px 20px; font-size: 14px;"><?php echo esc_html__( 'Maybe later', 'stvc_validatecertify' ); ?></a>
                        <a href="https://wordpress.org/support/plugin/validar-certificados-de-cursos/reviews/#new-post" target="_black" class="button button-secondary" style="margin-right: 10px; padding: 5px 20px;"><?php echo esc_html__( 'Leave a review', 'stvc_validatecertify' ); ?></a>
                    </p>
                </div>
            </div>
        </div>
        <?php
        // Elimina el transient para mostrar la notificación solo una vez
        delete_transient('stvc-admin-notice-plugin');
    }
}

add_action('admin_notices', 'stvc_admin_notice_plugin_notice');

function stvc_admin_notice_plugin_script() {
    if (get_transient('stvc-admin-notice-plugin')) {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '.stvc-notice .notice-dismiss', function(e) {
                    e.preventDefault();
                    var notice = $(this).closest('.stvc-notice');
                    notice.fadeOut(300, function() {
                        notice.remove();
                    });
                });

                $(document).on('click', '.stvc-notice [data-type="later"]', function(e) {
                    e.preventDefault();
                    var notice = $(this).closest('.stvc-notice');
                    var repeatNoticeAfter = parseInt(notice.data('repeat-notice-after'));
                    var currentTime = Math.floor(Date.now() / 1000);
                    var nextNoticeTime = currentTime + repeatNoticeAfter;
                    set_transient('stvc-admin-notice-plugin', true, nextNoticeTime - currentTime);
                    notice.fadeOut(300, function() {
                        notice.remove();
                    });
                });
            });
        </script>
        <?php
    }
}
add_action('admin_footer', 'stvc_admin_notice_plugin_script');