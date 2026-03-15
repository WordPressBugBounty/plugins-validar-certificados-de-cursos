<?php
/**
 * ValidateCertify Activator
 *
 * Este archivo crea la tabla necesaria en la base de datos para almacenar 
 * los datos relacionados con los certificados validados. 
 * Se ejecuta al activar el plugin.
 *
 * @package ValidateCertify
 */

global $wpdb;
// Nombre de la tabla en la base de datos con prefijo
$tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';

// Configuración del conjunto de caracteres y cotejamiento de la base de datos
$charset_collate = $wpdb->get_charset_collate();

// Consulta SQL para crear la tabla si no existe
    $consulta = "CREATE TABLE IF NOT EXISTS $tabla_stvc_validatecertify (
        id int(11) NOT NULL AUTO_INCREMENT,
        nombre varchar(255) NOT NULL,
        apellido varchar(255) NOT NULL,
        curso varchar(255) NOT NULL,
        fecha date NOT NULL,
        codigo varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $consulta );
    
?>