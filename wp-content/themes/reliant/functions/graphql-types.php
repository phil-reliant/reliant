<?php

add_action('graphql_register_types', function () {
    register_graphql_field('Page', 'pageTemplate', [
        'type' => 'String',
        'description' => 'WordPress Page Template',
        'resolve' => function ($page) {
            return get_page_template_slug($page->pageId);
        },
    ]);
});