<?php
/**
 *
 * Codestar Framework Example Widget
 *
 */
/*
https://www.w3schools.com/html/html_form_input_types.asp

'post_type'                 => 'post',          // Dropdown
'posts_per_page'            => 4,               // range (1-20)
'posts_per_row'             => 2,               // range (1-4)
'display_title'             => false,           // checkbox
'display_pagination'        => true,            // checkbox
'display_filter'            => true,            // checkbox
'blog_layout'               => 'top',           // dropdown (top, left, right, zigzag, none)
'no-gap'                    => false,           // checkbox
'except_lenght'             => 0,               // range (0-100)
'except_more'               => '',              // text
'pagination'                => 'pagination',    // dropdown (pagination, readmore, infinite)
'multi_selectable'          => true,            // checkbox
'thumbnail-size-single-row' => 'full',          // dropdown (thumbnail sizes)
'thumbnail-size-multi-row'  => 'large',         // dropdown (thumbnail sizes)
'taxonomies_terms'          => 'category',      // multiselect (based on post type)
'update_paged'              => false,           // checkbox
'display_page_number'       => false,           // checkbox
'paged'                     => 1,               // text (number)
'effect'                    => 'apollo',        // dropdown
'search'                    => '',              // text
'store_session'             => true,            // checkbox
 */
if( ! class_exists( 'exopite_multifilter_CS_Widget' ) ) {
    class exopite_multifilter_CS_Widget extends WP_Widget {

        function __construct() {

            $widget_args     = array(
                'class'   => 'cs_widget_example',
                'description' => 'Exopite Multifilter Widget.'
            );

            parent::__construct( 'cs_widget', 'Exopite Multifilter Widget', $widget_args );

        }

        function widget( $args, $instance ) {

            extract( $args );

            echo $before_widget;

            echo "Exopite Multifilter Widget";

            // if ( ! empty( $instance['title'] ) ) {
            //     echo $before_title . $instance['title'] . $after_title;
            // }

            // echo '<div class="textwidget">';
            // echo '<img src="'. $instance['logo'] .'" />';
            // echo '<p>'. $instance['content'] .'</p>';
            // echo '</div>';

            echo $after_widget;

        }

        function update( $new_instance, $old_instance ) {

            $instance            = $old_instance;
            $instance['title']   = $new_instance['title'];
            $instance['logo']    = $new_instance['logo'];
            $instance['sure']    = $new_instance['sure'];
            $instance['content'] = $new_instance['content'];

            return $instance;

        }

        function get_post_types() {
            $post_types = get_post_types( array(), 'objects' );
            $returned = array();
            $to_skip = array( 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset' );
            foreach ( $post_types as $post_type ) {
                if ( in_array( $post_type->name, $to_skip ) ) continue;
                $returned += [$post_type->name => esc_attr( $post_type->label )];
            }
            return $returned;
        }

        function form( $instance ) {

            //
            // set defaults
            // -------------------------------------------------
            $instance   = wp_parse_args( $instance, array(
                'post_type'                 => 'post',          // Dropdown
                'posts_per_page'            => 4,               // range (1-20)
                'posts_per_row'             => 2,               // range (1-4)
                'display_title'             => false,           // checkbox
                'display_pagination'        => true,            // checkbox
                'display_filter'            => true,            // checkbox
                'blog_layout'               => 'top',           // dropdown (top, left, right, zigzag, none)
                'no-gap'                    => false,           // checkbox
                'except_lenght'             => 0,               // range (0-100)
                'except_more'               => '',              // text

                'pagination'                => 'pagination',    // dropdown (pagination, readmore, infinite)
                'multi_selectable'          => true,            // checkbox
                'thumbnail-size-single-row' => 'full',          // dropdown (thumbnail sizes)
                'thumbnail-size-multi-row'  => 'large',         // dropdown (thumbnail sizes)
                'taxonomies_terms'          => 'category',      // multiselect (based on post type)
                'update_paged'              => false,           // checkbox
                'display_page_number'       => false,           // checkbox
                'paged'                     => 1,               // text (number)
                'effect'                    => 'apollo',        // dropdown
                'search'                    => '',              // text
                'store_session'             => true,            // checkbox
            ));

            //
            // text field example
            // -------------------------------------------------
            // $text_value = esc_attr( $instance['post_type'] );
            // $text_field = array(
            //     'id'    => $this->get_field_name('post_type'),
            //     'name'  => $this->get_field_name('post_type'),
            //     'type'  => 'text',
            //     'title' => 'Title',
            // );

            // -------------------------------------------------
            $post_type_value = esc_attr( $instance['post_type'] );
            $post_type_field = array(
                'id'    => $this->get_field_name('post_type'),
                'name'  => $this->get_field_name('post_type'),
                'type'  => 'select',
                'options'  => $this->get_post_types(),
                'title' => 'Post type',
                'class'          => 'chosen',
                'attributes'     => array(
                  'style' => 'width: 260px'
                ),
            );

            echo cs_add_element( $post_type_field, $post_type_value );

            // -------------------------------------------------
            $posts_per_page_value = esc_attr( $instance['posts_per_page'] );
            $posts_per_page_field = array(
                'id'    => $this->get_field_name('posts_per_page'),
                'name'  => $this->get_field_name('posts_per_page'),
                'type'  => 'slider',
                'title' => 'Posts per page',
                'options'   => array(
                    'step'    => 1,
                    'min'     => 1,
                    'max'     => 20,
                    'unit'    => ''
                ),
            );

            echo cs_add_element( $posts_per_page_field, $posts_per_page_value );

            // -------------------------------------------------
            $posts_per_row_value = esc_attr( $instance['posts_per_row'] );
            $posts_per_row_field = array(
                'id'    => $this->get_field_name('posts_per_row'),
                'name'  => $this->get_field_name('posts_per_row'),
                'type'  => 'slider',
                'title' => 'Posts per row',
                'options'   => array(
                    'step'    => 1,
                    'min'     => 1,
                    'max'     => 4,
                    'unit'    => ''
                ),
            );

            echo cs_add_element( $posts_per_row_field, $posts_per_row_value );

            // -------------------------------------------------
            $except_lenght_value = esc_attr( $instance['except_lenght'] );
            $except_lenght_field = array(
                'id'    => $this->get_field_name('except_lenght'),
                'name'  => $this->get_field_name('except_lenght'),
                'type'  => 'slider',
                'title' => 'Except lenght',
                'options'   => array(
                    'step'    => 1,
                    'min'     => 0,
                    'max'     => 100,
                    'unit'    => ''
                ),
            );

            echo cs_add_element( $except_lenght_field, $except_lenght_value );

            // -------------------------------------------------
            $blog_layout_value = esc_attr( $instance['blog_layout'] );
            $blog_layout_field = array(
                'id'    => $this->get_field_name('blog_layout'),
                'name'  => $this->get_field_name('blog_layout'),
                'type'  => 'select',
                'options'  => array( 'top', 'left', 'right', 'zigzag', 'none' ),
                'title' => 'List layout',
                'class'          => 'chosen',
                'attributes'     => array(
                  'style' => 'width: 260px'
                ),
            );

            echo cs_add_element( $blog_layout_field, $blog_layout_value );

            // -------------------------------------------------
            $except_more_value = esc_attr( $instance['except_more'] );
            $except_more_field = array(
                'id'    => $this->get_field_name('except_more'),
                'name'  => $this->get_field_name('except_more'),
                'type'  => 'text',
                'title' => 'Except more',
            );

            echo cs_add_element( $except_more_field, $except_more_value );

            // -------------------------------------------------
            $display_title_value = esc_attr( $instance['display_title'] );
            $display_title_field = array(
                'id'    => $this->get_field_name('display_title'),
                'name'  => $this->get_field_name('display_title'),
                'type'  => 'switcher',
                'title' => 'Display title',
            );

            echo cs_add_element( $display_title_field, $display_title_value );

            // -------------------------------------------------
            $display_pagination_value = esc_attr( $instance['display_pagination'] );
            $display_pagination_field = array(
                'id'    => $this->get_field_name('display_pagination'),
                'name'  => $this->get_field_name('display_pagination'),
                'type'  => 'switcher',
                'title' => 'Display pagination',
            );

            echo cs_add_element( $display_pagination_field, $display_pagination_value );

            // -------------------------------------------------
            $display_filter_value = esc_attr( $instance['display_filter'] );
            $display_filter_field = array(
                'id'    => $this->get_field_name('display_filter'),
                'name'  => $this->get_field_name('display_filter'),
                'type'  => 'switcher',
                'title' => 'Display filter',
            );

            echo cs_add_element( $display_filter_field, $display_filter_value );

            // -------------------------------------------------
            $no_gap_value = esc_attr( $instance['no-gap'] );
            $no_gap_field = array(
                'id'    => $this->get_field_name('no-gap'),
                'name'  => $this->get_field_name('no-gap'),
                'type'  => 'switcher',
                'title' => 'No gap',
            );

            echo cs_add_element( $no_gap_field, $no_gap_value );







        }
    }
}


