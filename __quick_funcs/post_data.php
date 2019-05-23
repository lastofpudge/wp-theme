<?php
        //
         if (!empty($_POST['test'])) {
             $test = sanitize_text_field($_GET['test']);
             $this->returned_data['posts'] = Timber::get_posts(
                [
                    'post_type'      => 'otzivi',
                    'posts_per_page' => 7,
                    'paged'          => $paged,
                    'order'          => 'DESC',
                    'meta_query'     => [
                        [
                                'key'     => 'test', // for custom fields use: "_fieldsname"
                                'value'   => $test,
                                'compare' => 'LIKE',
                            ],
                    ],
                ]);
         }
