<?php

    namespace models;


    class customModel extends Model
    {
        public static $table_name = 'example';

        public function __construct(){
            parent::__construct();
            self::up();
//            self::down();
        }

        /*
         * create table
         */
        public function up(){
            $table_name = self::$table_name;
            $table = $this->db->prefix . $table_name;

            $sql = "CREATE TABLE IF NOT EXISTS $table
            (
              id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY
            ) $this->charset;";

            $this->db->query($sql);
        }

        /*
         * drop table
         */
        public function down(){
           $table_name = self::$table_name;
           $table = $this->db->prefix . $table_name;

           $sql = "DROP TABLE {$table}";
           $this->db->query($sql);
        }
    }

    $cm = new customModel();