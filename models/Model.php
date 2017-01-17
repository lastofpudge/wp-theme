<?php

    namespace models;
    
    class Model
    {
        protected $data;

        public function __construct()
        {
            $this->getDB();
        }

        protected function getDB()
        {
            /*
             * get db
             */
            global $wpdb;
            $this->db = $wpdb;

            /*
             * get charset
             */
            $this->charset = $wpdb->get_charset_collate();
        }
    }
