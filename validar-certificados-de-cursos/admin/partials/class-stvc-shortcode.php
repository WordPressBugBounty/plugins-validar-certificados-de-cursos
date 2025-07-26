<?php
/* ValidateCertify Free ShortCode
 *
 * @class    stvc-shortcode
 * @package  ValidateCertify
 */

 function stvc_shortcode() {
    // Obtener el código ingresado por el usuario
    $codigo = isset($_POST['codigomuestra']) ? sanitize_text_field($_POST['codigomuestra']) : '';

    // Generar la clave de caché
    $cache_key = 'certificado_' . $codigo;

    // Intentar obtener los datos del certificado desde la caché
    $certificado = wp_cache_get($cache_key);

    if (false === $certificado && !empty($codigo)) {
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';

        // Aplicar filtro para la consulta
        $certificado = apply_filters(
            'stvc_get_certificado',
            $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s",
                    $codigo
                )
            ),
            $codigo
        );

        if ($certificado) {
            wp_cache_set($cache_key, $certificado, '', 3600);
        }
    }

    // Generar el HTML del widget
    $output = '';

    // Si el certificado existe, mostrar los datos en una tabla
    if ($certificado) {
        $nombre = $certificado->nombre;
        $apellido = $certificado->apellido;
        $curso = $certificado->curso;
        $fecha = $certificado->fecha;

        $output .= '<table class="certificado-table" "centered-content-stvc">';
        $output .= '<tr><td><strong>' . esc_html__('Code:', 'stvc_validatecertify') . '</strong></td><td>' . $codigo . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__('Name:', 'stvc_validatecertify') . '</strong></td><td>' . $nombre . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__('Last Name:', 'stvc_validatecertify') . '</strong></td><td>' . $apellido . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__('Course:', 'stvc_validatecertify') . '</strong></td><td>' . $curso . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__('Date of issue:', 'stvc_validatecertify') . '</strong></td><td>' . $fecha . '</td></tr>';
        $output .= '</table>';
        $output .= '<br>';
        $output .= '<div class="centered-content-stvc">';
        $output .= '<span class="invalid-code">' . esc_html__('You want to perform another search?', 'stvc_validatecertify') . '</span>';
        $output .= '</div>';
    } else if (!empty($codigo)) {
        // Mostrar mensaje de error si el código no es válido
        $output .= '<div class="centered-content-stvc">';
        $output .= '<span id="mensaje-validacion" class="validation-message error-vc">' . esc_html__('The code entered is not valid. Please enter a valid code.', 'stvc_validatecertify') . '</span>';
        $output .= '</div>';
    } else {
        // Mensaje predeterminado si no se ha ingresado un código
        $output .= '<div class="centered-content-stvc">';
        $output .= '<span id="mensaje-validacion" class="validation-message">' . esc_html__('Please enter a Valid Certificate Code', 'stvc_validatecertify') . '</span>';
        $output .= '</div>';
    }

    // Mostrar el formulario para ingresar el código
    $output .= '<div class="centered-content-stvc">';
    $output .= '<form method="post">';
    $output .= '<input type="text" id="codigomuestra" name="codigomuestra" class="regular-text" placeholder="' . esc_attr__('Enter the code here', 'stvc_validatecertify') . '" > ';
    $output .= '<input type="submit" value="' . esc_attr__('Consult', 'stvc_validatecertify') . '">';
    $output .= '</form>';
    $output .= '</div>';

    wp_enqueue_style('validatecertify-styles', plugins_url('assets/css/validatecertify-styles.css', __FILE__));

    return $output;
}

add_shortcode( 'ValidateCertify', 'stvc_shortcode' );