<?php

/* ValidateCertify Menu
 *
 * @class    stvc-menu
 * @package  ValidateCertify
 */

function stvc_menu() {
    add_menu_page(
        esc_html__( 'ValidateCertify', 'stvc_validatecertify' ), // Título de la página
        esc_html__( 'ValidateCertify', 'stvc_validatecertify' ), // Título del menú
        'manage_options', // Capacidad requerida para acceder a la página
        'validatecertify', // Slug del menú
        'stvc_basededatos', // Función que muestra la página
        'dashicons-awards', // Icono del menú
        30 
    );
    add_submenu_page(
        'validatecertify',
        esc_html__( 'Add New Certificate', 'stvc_validatecertify' ),
        esc_html__( 'Add New Certificate', 'stvc_validatecertify' ),
        'manage_options',
        'new_certificates_stvc',
        'stvc_certificado_nuevo'
    );
    add_submenu_page(
        'validatecertify',
        esc_html__( 'Edit Certificate', 'stvc_validatecertify' ),
        esc_html__( 'Edit Certificate', 'stvc_validatecertify' ),
        'manage_options',
        'edit_certificates_stvc',
        'stvc_modificar_certificados'
    );
    add_submenu_page(
        'validatecertify',
        esc_html__( 'Delete Certificate', 'stvc_validatecertify' ),
        esc_html__( 'Delete Certificate', 'stvc_validatecertify' ),
        'manage_options',
        'delete_certificates_stvc',
        'stvc_eliminar_certificado'
    );
    add_submenu_page(
        'validatecertify',
        esc_html__( 'Tools', 'stvc_validatecertify' ),
        esc_html__( 'Tools', 'stvc_validatecertify' ),
        'manage_options',
        'tools_validatecertify',
        'stvc_herramientas'
    );
}

add_action( 'admin_menu', 'stvc_menu' );

function stvc_basededatos() {
    
    global $wpdb;
    $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
    $registros_por_pagina = isset($_GET['registros']) && $_GET['registros'] === '50' ? 50 : 20;
    $pagina_actual = isset($_GET['pagina']) ? absint($_GET['pagina']) : 1;
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    // Generar la clave de caché para el total de registros
    $cache_key_total_registros = 'stvc_total_registros';

    // Intentar obtener el total de registros desde la caché
    $total_registros = wp_cache_get($cache_key_total_registros);

    // Si no se encuentra en la caché, obtener el total de registros desde la base de datos
    if (false === $total_registros) {
        $total_registros = $wpdb->get_var("SELECT COUNT(*) FROM $tabla_stvc_validatecertify");

        // Almacenar el total de registros en la caché
        wp_cache_set($cache_key_total_registros, $total_registros, '', 3600); // Cachear por 1 hora
    }

    $total_paginas = ceil($total_registros / $registros_por_pagina);

    if (isset($_POST['buscar_codigo'])) {
        $codigo_buscar = isset($_POST['codigo_buscar']) ? sanitize_text_field($_POST['codigo_buscar']) : '';
        $resultados = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s", $codigo_buscar));
    } else {
        $resultados = $wpdb->get_results("SELECT * FROM $tabla_stvc_validatecertify LIMIT $offset, $registros_por_pagina");
    }

    ?>
        <div id="encabezado-menu" class="#top-menu">
            <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Certificates Issued', 'stvc_validatecertify' ); ?></h1>
        </div>
        <div class="title-page-st">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Certificate Base', 'stvc_validatecertify' ); ?></h1>
            <a href="admin.php?page=new_certificates_stvc" class="page-title-action button-primary"><?php esc_html_e( 'Add Certificate', 'stvc_validatecertify' ); ?></a>
        </div>

        <div class="wrap">
            <hr class="wp-header-end">
            <!-- Formulario de búsqueda -->
            <form method="post" >
                <p class="search-box">
                <label for="codigo_buscar"><strong><?php esc_html_e( 'Search by Code:', 'stvc_validatecertify' ); ?></strong></label>
                <input type="text" name="codigo_buscar" id="codigo_buscar" required class="text" placeholder="<?php esc_attr_e( 'Enter the code here', 'stvc_validatecertify' ); ?>">
                <input type="submit" name="buscar_codigo" class="button button-secondary" value="<?php esc_attr_e( 'Search', 'stvc_validatecertify' ); ?>"></p>
            </form>
            
            <!-- Texto de paginación -->
            <p style="align-self: center;">
                <strong><?php esc_html_e( 'Show groups of:', 'stvc_validatecertify' ); ?></strong>
                <a href="<?php echo esc_url(add_query_arg('registros', 20)); ?>">20</a> |
                <a href="<?php echo esc_url(add_query_arg('registros', 50)); ?>">50</a>
            </p>
        </div>

        <div class="wrap">
            <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                <th><strong><?php esc_html_e( 'Name', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Last Name', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Course', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Date', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Code', 'stvc_validatecertify' ); ?></strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($resultados as $fila) {
                    echo '<tr>';
                    echo '<td>' . esc_html($fila->nombre) . '</td>';
                    echo '<td>' . esc_html($fila->apellido) . '</td>';
                    echo '<td>' . esc_html($fila->curso) . '</td>';
                    echo '<td>' . esc_html($fila->fecha) . '</td>';
                    echo '<td>' . esc_html($fila->codigo) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
            </table>
                <!-- Botones de Paginación -->
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    // Texto "x certificados"
                    echo '<span class="certificados-total">' . sprintf(esc_html__('%02s certificates', 'stvc_validatecertify'), esc_html($total_registros)) . '</span>';
                    
                    // Botón para ir a la primera página
                    echo '<a class="first-page button' . ($pagina_actual <= 1 ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', 1)) . '" style="margin-left: 5px;">&laquo; </a>';
                    
                    // Botón para ir a la página anterior
                    echo '<a class="prev-page button' . ($pagina_actual <= 1 ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', max($pagina_actual - 1, 1))) . '" style="margin-left: 5px;">&lsaquo; </a>';
                    
                    // Mostrar el texto de la página actual
                    echo '<span class="current-page" style="margin: 0 5px;">' . sprintf(esc_html__('Page %1$02s de %1$02s', 'stvc_validatecertify'), esc_html($pagina_actual), esc_html($total_paginas)) . '</span>';

                    
                    // Botón para ir a la página siguiente
                    echo '<a class="next-page button' . ($pagina_actual >= $total_paginas ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', min($pagina_actual + 1, $total_paginas))) . '" style="margin-left: 5px;"> &rsaquo;</a>';
                    
                    // Botón para ir a la última página
                    echo '<a class="last-page button' . ($pagina_actual >= $total_paginas ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', $total_paginas)) . '" style="margin-left: 5px;"> &raquo;</a>';
                    ?>
                </div>
            </div>
        </div>

    <?php
}

function stvc_certificado_nuevo() {
    if (isset($_POST['guardar_certificado'])) {
        // Verificar los permisos del usuario
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Access denied', 'stvc_validatecertify'));
        }
   
        if (!isset($_POST['stvc_nonce']) || !wp_verify_nonce($_POST['stvc_nonce'], 'stvc_guardar_certificado_nonce')) {
            wp_die(esc_html__('Access denied', 'stvc_validatecertify'));
        }

        // Validación y saneamiento de los datos
        $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
        $apellido = isset($_POST['apellido']) ? sanitize_text_field($_POST['apellido']) : '';
        $curso = isset($_POST['curso']) ? sanitize_text_field($_POST['curso']) : '';
        $fecha = isset($_POST['fecha']) ? sanitize_text_field($_POST['fecha']) : '';
        $codigo = isset($_POST['codigo']) ? sanitize_text_field($_POST['codigo']) : '';

        // Guardar los datos en la base de datos
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
        $wpdb->insert($tabla_stvc_validatecertify, array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'curso' => $curso,
            'fecha' => $fecha,
            'codigo' => $codigo
        ));

        // Mostrar mensaje de éxito utilizando add_notice
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('The certificate has been saved correctly.', 'stvc_validatecertify') . '</p></div>';
    }
    ?>
        <div id="encabezado-menu" class="#top-menu">
            <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Add New Certificate', 'stvc_validatecertify' ); ?></h1>
        </div>
        <div class="ui form">
            <div class="title-page-st">
                <h1 class="wp-heading-inline"><?php esc_html_e( 'Add New Certificate', 'stvc_validatecertify' ); ?></h1>
            </div>
            <p><?php esc_html_e( 'Add a new record to the certificate database', 'stvc_validatecertify' ); ?></p>
            <hr class="wp-header-end">
        </div>
        <div>
            <form method="post" class="ui form">
                <?php wp_nonce_field('stvc_guardar_certificado_nonce', 'stvc_nonce'); ?>
                <div class="field">
                <div class="field">
                    <label><?php esc_html_e('Code', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="codigo" type="text" id="codigo" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Name', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="nombre" type="text" id="nombre" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Last Name', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="apellido" type="text" id="apellido" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Course', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="curso" type="text" id="curso" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Date', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="fecha" type="date" id="fecha" required>
                    </div>
                </div>
                <input type="submit" name="guardar_certificado" id="guardar_certificado" class="button button-primary" value="<?php esc_attr_e('Save Certificate', 'stvc_validatecertify'); ?>">
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('form');
                    const fechaInput = document.getElementById('fecha');

                    form.addEventListener('submit', function(event) {
                        const fechaValor = fechaInput.value;

                        const anio = parseInt(fechaValor.split('-')[0], 10);
                        if (anio < 1950 || anio > 2099) {
                            alert('<?php esc_html_e('The date must be between the years 1950 and 2099.', 'stvc_validatecertify'); ?>');
                            event.preventDefault(); // Detiene el envío del formulario
                        }
                    });
                });
            </script>
            </form>
        </div>
    <?php
}

function stvc_modificar_certificados() {
    global $wpdb;
    if (isset($_POST['modificar_codigo'])) {
        $codigo = sanitize_text_field($_POST['modificar_codigo']);
    
        // Generar la clave de caché
        $cache_key = 'certificado_' . $codigo;
    
        // Intentar obtener el certificado desde la caché
        $certificado = wp_cache_get($cache_key);
    
        // Si no se encuentra en la caché, obtenerlo desde la base de datos
        if (false === $certificado) {
            $certificado = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}stvc_validatecertify WHERE codigo = %s", $codigo));
    
            // Si se encuentra el certificado, almacenarlo en la caché
            if ($certificado) {
                wp_cache_set($cache_key, $certificado, '', 3600); // Cachear por 1 hora
            }
        }
    
        if ($certificado) {
            if (isset($_POST['guardar'])) {
                $nombre = sanitize_text_field($_POST['nombre']);
                $apellido = sanitize_text_field($_POST['apellido']);
                $curso = sanitize_text_field($_POST['curso']);
                $fecha = sanitize_text_field($_POST['fecha']);
                $wpdb->update(
                    "{$wpdb->prefix}stvc_validatecertify",
                    array(
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'curso' => $curso,
                        'fecha' => $fecha
                    ),
                    array('codigo' => $codigo)
                );
    
                // Invalidar la caché después de la actualización
                wp_cache_delete($cache_key);
    
                // Obtener los datos actualizados después de la actualización
                $certificado = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}stvc_validatecertify WHERE codigo = %s", $codigo));
    
                // Actualizar la caché con los datos actualizados
                wp_cache_set($cache_key, $certificado, '', 3600)
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__('Certificate modified successfully!', 'stvc_validatecertify'); ?></p>
                </div>
                <?php
            }
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                    <div class="ui form">
                        <div class="title-page-st">
                            <h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
                        </div>
                        <p><?php esc_html_e( 'Update your certificate data, remember that once done, previous data will be lost.', 'stvc_validatecertify' ); ?></p>
                        <hr class="wp-header-end">
                    </div>
                    <form method="post" class="ui form">
                        <input type="hidden" name="modificar_codigo" value="<?php echo esc_attr($codigo); ?>">
                        
                            <div class="field">
                                <label><?php echo esc_html__('Name:', 'stvc_validatecertify'); ?></label>
                                <input type="text" name="nombre" id="nombre" class="regular-text" value="<?php echo esc_attr($certificado->nombre); ?>">
                            </div>
                            <div class="field">
                                <label><?php echo esc_html__('Last Name:', 'stvc_validatecertify'); ?></label>
                                <input type="text" name="apellido" id="apellido" class="regular-text" value="<?php echo esc_attr($certificado->apellido); ?>">
                            </div>
                            <div class="field">
                                <label><?php echo esc_html__('Course:', 'stvc_validatecertify'); ?></label>
                                <input type="text" name="curso" id="curso" class="regular-text" value="<?php echo esc_attr($certificado->curso); ?>">
                            </div>
                            <div class="field">
                                <label><?php echo esc_html__('Date:', 'stvc_validatecertify'); ?></label>
                                <input type="date" name="fecha" id="fecha" value="<?php echo esc_attr($certificado->fecha); ?>">
                            </div>
                        <input type="submit" name="guardar" class="button button-primary" value="<?php echo esc_attr__('Update certificate', 'stvc_validatecertify'); ?>">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=edit_certificates_stvc')); ?>" class="ui secondary button"><?php echo esc_html__('Cancel', 'stvc_validatecertify'); ?></a>
                    </form>
            <?php
            } else {
                ?>
                    <div id="encabezado-menu" class="#top-menu">
                        <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <div class="ui form">
                        <div class="title-page-st">
                            <h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
                        </div>
                        <p><?php echo esc_html__('The certificate is not valid. Please enter a ', 'stvc_validatecertify'); ?> <strong><?php echo esc_html__('Valid Certificate Code ', 'stvc_validatecertify'); ?></strong> <?php echo esc_html__('to modify names, last names, courses and/or issue date.', 'stvc_validatecertify'); ?></p>
                        <hr class="wp-header-end">
                    </div>
                    <form method="post" class="ui form">
                        <div class="field">
                        <label for="codigo"><strong><?php echo esc_html__('Code:', 'stvc_validatecertify'); ?> </strong></label>
                        <input type="text" name="modificar_codigo" id="codigo" class="regular-text" placeholder="<?php echo esc_attr__('Enter the code here', 'stvc_validatecertify'); ?>">
                    </div>
                        <input type="submit" class="button button-primary" value="<?php echo esc_attr__('Search certificate', 'stvc_validatecertify'); ?>">
                        </form>
                <?php
            }
        } else {
            
        ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
            </div>
            <div class="ui form">
                <div class="title-page-st">                            
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                <p><?php esc_html_e( 'Enter the Certificate code to modify, to update the names, last name, courses and/or date of issue.', 'stvc_validatecertify' ); ?></p>
                <hr class="wp-header-end">
            </div>
            <form method="post" class="ui form">
                <div class="field">
                    <label><?php echo esc_html__('Code:', 'stvc_validatecertify'); ?></label>
                    <input type="text" name="modificar_codigo" id="codigo" class="text" placeholder="<?php echo esc_attr__('Enter the code here', 'stvc_validatecertify'); ?>">
                </div>
                <input type="submit" class="button button-primary" value="<?php echo esc_attr__('Search certificate', 'stvc_validatecertify'); ?>">
            </form>
        <?php
    }
}

function stvc_eliminar_certificado() {
global $wpdb;
    $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';

    if (isset($_POST['eliminar_certificado_confirmar']) && isset($_POST['codigo_eliminar'])) {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Access denied', 'stvc_validatecertify'));
        }

        $codigo_eliminar = sanitize_text_field($_POST['codigo_eliminar']);

        // Invalidar la caché antes de eliminar el certificado
        $cache_key = 'certificado_' . $codigo_eliminar;
        wp_cache_delete($cache_key);

        // Utilizar wpdb::prepare() para eliminar el certificado
        $wpdb->delete(
            $tabla_stvc_validatecertify,
            array('codigo' => $codigo_eliminar),
            array('%s') // Especificar el formato del valor
        );

        if ($certificado_eliminado) {
            // El certificado se eliminó correctamente, muestra un mensaje de éxito y el botón Volver
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'The certificate was removed from the database.', 'stvc_validatecertify' ); ?></p>
                        <hr class="wp-header-end">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=delete_certificates_stvc')); ?>" class="ui primary button"><?php echo esc_html__('Return', 'stvc_validatecertify'); ?></a>
                </div>
            <?php
            } else {
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'Could not delete certificate, an error occurred.', 'stvc_validatecertify' ); ?></p>
                    <hr class="wp-header-end">
                    <br>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=delete_certificates_stvc')); ?>" class="ui primary button"><?php echo esc_html__('Search again', 'stvc_validatecertify'); ?></a>
                </div>
            <?php
        }
    } elseif (isset($_POST['eliminar_certificado']) && isset($_POST['codigo_eliminar'])) {
        $codigo_eliminar = sanitize_text_field($_POST['codigo_eliminar']);

        // Generar la clave de caché
        $cache_key = 'certificado_' . $codigo_eliminar;

        // Intentar obtener el certificado desde la caché
        $certificado = wp_cache_get($cache_key);

        // Si no se encuentra en la caché, obtenerlo desde la base de datos
        if (false === $certificado) {
            // Utilizar wpdb::prepare() para obtener el certificado
            $certificado = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s", $codigo_eliminar)
            );

            // Si se encuentra el certificado, almacenarlo en la caché
            if ($certificado) {
                wp_cache_set($cache_key, $certificado, '', 3600); // Cachear por 1 hora
            }
        }

        if ($certificado) {
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'Are you sure you want to delete the following certificate?', 'stvc_validatecertify' ); ?></p>

                    <p><strong><?php echo esc_html__('Name:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->nombre); ?></p>
                    <p><strong><?php echo esc_html__('Last Name:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->apellido); ?></p>
                    <p><strong><?php echo esc_html__('Course:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->curso); ?></p>
                    <p><strong><?php echo esc_html__('Date:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->fecha); ?></p>
                    <p><strong><?php echo esc_html__('Code:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->codigo); ?></p>

                    <form method="post" class="ui form">
                        <input type="hidden" name="codigo_eliminar" value="<?php echo esc_attr($codigo_eliminar); ?>">
                        <button type="submit" class="ui primary button" name="eliminar_certificado_confirmar"><?php echo esc_html__('Delete Certificates', 'stvc_validatecertify'); ?></button>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=delete_certificates_stvc')); ?>" class="ui secondary button"><?php echo esc_html__('Cancel', 'stvc_validatecertify'); ?></a>
                    </form>
                </div>
            <?php
        } else {
            // No se encontró el certificado con el código proporcionado
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'No certificate was found with that code, please enter a valid code.', 'stvc_validatecertify' ); ?></p>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=delete_certificates_stvc')); ?>" class="ui primary button"><?php echo esc_html__('Search again', 'stvc_validatecertify'); ?></a>
                </div>
            <?php
        }
    } else {
        ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
            </div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Delete Certificates', 'stvc_validatecertify' ); ?></h1>
                    </div>
                <p><?php esc_html_e( 'Enter the Certificate code to delete, once deleted it cannot be recovered.', 'stvc_validatecertify' ); ?></p>
                <hr class="wp-header-end">
            </div>
            <form method="post" class="ui form">
            <div class="field">
                <label><?php echo esc_html__('Code:', 'stvc_validatecertify'); ?></label>
                <input type="text" name="codigo_eliminar" class="regular-text" id="codigo_eliminar" required placeholder="<?php echo esc_attr__('Enter the code here', 'stvc_validatecertify'); ?>">
                </div>
                <input type="submit" class="button button-primary" name="eliminar_certificado" value="<?php echo esc_attr__('Search Certificates', 'stvc_validatecertify'); ?>">
            </form>
        <?php
    }
}

function stvc_herramientas() {
    ?>
        <div id="encabezado-menu" class="#top-menu">
            <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Tools', 'stvc_validatecertify' ); ?></h1>
        </div>
            <div class="ui form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Tools', 'stvc_validatecertify' ); ?></h1>
                    </div>
                <h3><?php esc_html_e('ShortCode ValidateCertify', 'stvc_validatecertify'); ?></h3>
                    <p><?php esc_html_e('Add the shortcode on the page where the certificates will be validated, the search will be by code.', 'stvc_validatecertify'); ?></p>
                <hr class="wp-header-end">
            </div>
        <form class="ui form">
            <div class="field">
                <div class="ui labeled input">
                    <input id="shortcodeInput" type="text" value="[ValidateCertify]" readonly>
                </div>
            </div>
            <div class="field">
                <button class="ui primary button" type="button" onclick="copyToClipboard()">
                    <?php esc_html_e('Copy Shortcode', 'stvc_validatecertify'); ?>
                </button>
            </div>
        </form>
        <script>
        function copyToClipboard() {
            const input = document.getElementById('shortcodeInput');
            input.select();
            document.execCommand('copy');
            alert('<?php esc_html_e('Shortcode copied to clipboard', 'stvc_validatecertify'); ?>');
        }
        </script>
    <?php    
}