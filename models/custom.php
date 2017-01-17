<?php

    namespace models;


    class customModel
    {
        public static $table_name = 'example';

        public function __construct(){
//            self::up();
            self::down();
        }

        public function up(){
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table = $wpdb->prefix . $table_name;

            $sql = "CREATE TABLE $table ( id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
        
        public function down(){
           global $wpdb;
           $table_name = self::$table_name;

           $table = $wpdb->prefix . $table_name;
           $sql = "DROP TABLE $table";
           require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta( $sql );
        }
    }

    $cm = new customModel();