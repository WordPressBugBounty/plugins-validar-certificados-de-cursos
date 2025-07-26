<?php
    global $wpdb;
    $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
    $charset_collate = $wpdb->get_charset_collate();
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