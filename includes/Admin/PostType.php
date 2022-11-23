<?php

namespace MailKit\Admin;


class PostType
{
    public function __construct()
    {
        add_action('admin_init',  [$this, 'addMailTemplateMetaCaps']);
        add_action('init', [$this, 'createEmailTemplateCpt']);
    }
    function addMailTemplateMetaCaps()
    {
        // gets the administrator role
        $admins = get_role('administrator');

        $admins->add_cap('edit_email_template');
        $admins->add_cap('edit_email_templates');
        $admins->add_cap('edit_other_email_templates');
        $admins->add_cap('publish_email_templates');
        $admins->add_cap('read_email_template');
        $admins->add_cap('read_private_email_templates');
        $admins->add_cap('delete_email_template');
    }

    // Register Custom Post Type Email Template
    function createEmailTemplateCpt()
    {
        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x('Email templates', 'Post Type General Name', 'mail-kit'),
            'singular_name'       => _x('Email template', 'Post Type Singular Name', 'mail-kit'),
            'menu_name'           => __('Email template', 'mail-kit'),
            'parent_item_colon'   => __('Parent email template', 'mail-kit'),
            'all_items'           => __('All email templates', 'mail-kit'),
            'view_item'           => __('View email template', 'mail-kit'),
            'add_new_item'        => __('Add New email template', 'mail-kit'),
            'add_new'             => __('Add New', 'mail-kit'),
            'edit_item'           => __('Edit email template', 'mail-kit'),
            'update_item'         => __('Update email template', 'mail-kit'),
            'search_items'        => __('Search email template', 'mail-kit'),
            'not_found'           => __('Not Found', 'mail-kit'),
            'not_found_in_trash'  => __('Not found in Trash', 'mail-kit'),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label'               => __('Email templates', 'mail-kit'),
            'description'         => __('Email templates', 'mail-kit'),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => ['title'],
            // You can associate this CPT with a taxonomy or custom taxonomy. 
            'taxonomies'          => [],

            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */

            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capabilities' => array(
                'edit_post' => 'edit_email_template',
                'edit_posts' => 'edit_email_templates',
                'edit_others_posts' => 'edit_other_email_templates',
                'publish_posts' => 'publish_email_templates',
                'read_post' => 'read_email_template',
                'read_private_posts' => 'read_private_email_templates',
                'delete_post' => 'delete_email_template'
            ),
            'show_in_rest' => true,
            'map_meta_cap' => true
        );

        // Registering your Custom Post Type
        register_post_type('email_template', $args);
    }
}
