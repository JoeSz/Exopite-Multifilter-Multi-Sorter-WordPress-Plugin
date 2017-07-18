<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Multifilter
 * @subpackage Exopite_Multifilter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Exopite_Multifilter
 * @subpackage Exopite_Multifilter/public
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Multifilter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Multifilter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Multifilter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if ( ! defined( EXOPITE_CORE_URL ) ) {
            if ( ! wp_style_is( 'bootstrap' ) && ! wp_style_is( 'bootstrap-4' ) ) {

                /*
                 * Enqueue scripts and styles with automatic versioning
                 *
                 * https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/
                 */
                $bootstrap_css_url  = plugin_dir_url( __FILE__ ) . 'css/bootstrap4-grid-light.min.css';
                $bootstrap_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap4-grid-light.min.css';
                wp_enqueue_style( 'bootstrap-light', $bootstrap_css_url, array(), filemtime( $bootstrap_css_path ), 'all' );
            }
        }

        $public_css_url  = plugin_dir_url( __FILE__ ) . 'css/exopite-multifilter-public.min.css';
        $public_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'exopite-multifilter-public.min.css';
        wp_enqueue_style( $this->plugin_name, $public_css_url, array(), filemtime( $public_css_path ) );

        $public_effect_css_url  = plugin_dir_url( __FILE__ ) . 'css/effects.min.css';
        $public_effect_css_path = EXOPITE_MULTIFILTER_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'effects.min.css';
        wp_enqueue_style( 'exopite-effects', $public_effect_css_url, array(), filemtime( $public_effect_css_path ) );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Multifilter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Multifilter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if ( ! defined( EXOPITE_CORE_URL ) || ! wp_script_is( 'exopite-core-js' ) ) {

            // https://www.doitwithwp.com/enqueue-scripts-styles-automatic-versioning/
            $core_js_url  = plugin_dir_url( __FILE__ ) . 'js/exopite-core.min.js';
            $core_js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'exopite-core.min.js';

            // Exopite core scripts (debounce, throttle, filter & action hooks)
            wp_enqueue_script( 'exopite-core-js', $core_js_url, array(), filemtime( $core_js_path ), true );

        }

        $public_js_url  = plugin_dir_url( __FILE__ ) . 'js/exopite-multifilter-public.min.js';
        $public_js_path = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'exopite-multifilter-public.min.js';

		wp_enqueue_script( $this->plugin_name, $public_js_url, array( 'jquery' ), filemtime( $public_js_path ), true );

        /**
         *  In backend there is global ajaxurl variable defined by WordPress itself.
         *
         * This variable is not created by WP in frontend. It means that if you want to use AJAX calls in frontend, then you have to define such variable by yourself.
         * Good way to do this is to use wp_localize_script.
         *
         * @link http://wordpress.stackexchange.com/a/190299/90212
         */
        wp_localize_script( $this->plugin_name, 'wp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

    function get_thumbnail( $post_id, $blog_layout = 'top', $thumbnail_size = 'medium', $effect = 'apollo' ) {

        $post_password_required = post_password_required();

        $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $thumbnail_size );
        $url = $thumbnail_url['0'];

        $class = '';

        if ( $blog_layout == ( 'left' || 'right' ) ) {
           $class = ' image-' . $blog_layout;
        }

        $effect = ( $effect != 'none' ) ? ' effect-' . $effect : '';
        $effect .= ( $post_password_required ) ? ' image-protected' : '';

        $ret = '';

        $ret .= '<div class="entry-thumbnail-container clearfix' . $class . '">';

        $ret .= '<a href="' . get_permalink( $post_id ) . '">';
        $ret .= '<figure class="effect-multifilter' . $effect . ' entry-thumbnail">'; //for animation
        $ret .= ( $post_password_required ) ? '' : '<img src="' . $url . '">';
        $ret .= '<figcaption>';
        $ret .= '<div class="figure-caption animation">' . get_the_title( $post_id ) . '</div>';
        $ret .= '</figcation>';
        $ret .= '</figure>';
        $ret .= '</a>';

        $ret .= '</div>';

        return $ret;

    }

    /**
     * in_array() on multidimensional arrays
     *
     * @link http://stackoverflow.com/questions/4128323/in-array-and-multidimensional-array
     * @param  string $item  needle to find
     * @param  array  $array haystack
     * @return boolean
     */
    function in_array_r($item , $array){
        return preg_match('/"'.$item.'"/i' , json_encode($array));
    }

    /**
     * Display selected taxonomies
     */
    function get_filters( $selected_post_type, $selected_taxonomies, $is_multifilter ) {

        $ret = '';

        $taxonomies = get_object_taxonomies( $selected_post_type, 'object' );

        foreach( $taxonomies as $taxonomy ){

            if ( ! $this->in_array_r( $taxonomy->name, $selected_taxonomies ) ) continue;

            $terms = get_terms( array(
                'taxonomy' => $taxonomy->name,
                'hide_empty' => true,
            ) );

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $ret .= '<div class="exopite-multifilter-filter-taxonomy exopite-multifilter-filter-taxonomy-' . $taxonomy->name . '" data-post-type="' . $selected_post_type . '" data-multiselect="' . $is_multifilter . '" data-taxonomy="' . $taxonomy->name . '">';
                foreach ( $terms as $term ) {
                    $ret .= '<span class="exopite-multifilter-filter-item exopite-multifilter-filter-item-' . $term->slug . '" data-term="' . $term->slug . '">' . $term->name . '</span>';
                }
                $ret .= '</div>';
            }

        }

        return $ret;

    }

    function excerpt_length() {
        return ExopiteSettings::getValue('exopite_multifilter_except_lenght');
    }

    function excerpt_more() {
        /**
         * ToDo:
         * - Excerpt -> leave the link, change only text and if text empty, leave it out
         */
        return '' . ExopiteSettings::getValue('exopite_multifilter_except_more') . '';
    }


    // create pagination link
    function get_pagination( $page_id, $max_num_page, $current_page, $pagination, $format = 'page' ) {
        if( $max_num_page <= 1 ) return '';

        $ret = '';

        if ( $pagination != 'pagination' ) {
            $next_url = get_the_permalink( $page_id ) . $format . '/' . ( $current_page + 1 ) . '/';
        }

        switch ( $pagination ) {
            case 'readmore':
                if ( $current_page < $max_num_page ) {
                    $ret .='<a href="' . $next_url . '" class="btn btn-material btn-readmore next">' . __( 'Read more', 'exopite-multifilter' ) . '</a>';
                } else {
                    $ret .= '<span class="nothing-more">' . __( 'Nothing more to load.', 'exopite-multifilter' ) . '</span>';
                }
                break;

            case 'infinite':
                $ret .= '<span class="next" data-next="' . $next_url . '"></span>';
                break;

            default:
                // pagination
                $args = array(
                    'base'      => get_the_permalink( $page_id ) . '%_%',
                    'format'    => $format . '/%#%/',
                    'current'   => max( 1, $current_page ),
                    'total'     => $max_num_page,
                    'prev_text' => esc_html__('&lsaquo;', 'exopite-multifilter' ),
                    'next_text' => esc_html__('&rsaquo;', 'exopite-multifilter' ),
                    'show_all'  => false,
                    'end_size'  => 1,
                    'mid_size'  => 2,
                    'type'               => 'array',
                );

                if( isset($_GET['s']) ){
                    $args['add_args'] = array(
                        's' => $_GET['s'] // your search query passed via your ajax function
                    );
                }

                $ret .= join( paginate_links( $args ) );
                break;
        }

        return '<div class="col-12">' . $ret . '</div>';

    }

    function get_articles( $args ) {

        // WP_Query: http://www.billerickson.net/code/wp_query-arguments/
        // Set post type
        $args['query'] = array(
            'post_type'     => $args['post_type'],
            'post_status'   => 'publish',
            's'             => $args['search'],
        );

        $ret = '';

        if ( $args['random'] ) {
            $args['display_pagination'] = false;
            $args['query']['orderby'] = 'rand';
        }

        $args['query']['tax_query']['relation'] = ( $args['in_all_taxnomies'] ) ? 'AND' : 'OR';

        // $args['query']['tax_query']['relation'] = 'AND';

        foreach ( $args['taxonomies_terms'] as $taxonomy => $terms ) {

            // Set taxonomies and terms
            if ( is_array( $terms ) && ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    $args['query']['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'terms' => $term,
                        'include_children' => true,
                        'field' => 'slug',
                    );
                }
            }

        }

        if ( isset( $args['search'] ) && ! $args['random'] ) {
            $args['query']['s'] = $args['search'];
        }

        // Pagination
        if ( ! $args['random'] ) {
            $args['query']['paged'] = $args['paged'];
        }
        $args['query']['posts_per_page'] = $args['posts_per_page'];
        //$args['query']['fields'] = 'ids';

        // START Deal with sticky posts
        $include_sticky = true;
        $args['query']['ignore_sticky_posts'] = 1;

        if ( ! $args['random'] && $include_sticky && $args['query']['post_type'] == 'post' ) {

            // http://www.kriesi.at/support/topic/sticky-posts-in-b-og-grid/
            $sticky = get_option( 'sticky_posts' );
            $sticky_args = $args['query'];
            $sticky_args['post__not_in'] = $sticky;
            $sticky_args['posts_per_page'] = -1;

            /**
             * Get only post ids from query
             *
             * @link http://wordpress.stackexchange.com/questions/166029/get-post-ids-from-wp-query/166034#166034
             */
            $sticky_args['fields'] = 'ids';

            $posts_query = new WP_Query( $sticky_args );
            $posts_ids = $posts_query->posts;

            if ( is_array( $sticky ) && is_array( $posts_ids ) ) {
                $posts_ids = array_merge( $sticky, $posts_ids );
            }

            $args['query']['post__in'] = $posts_ids;

            $args['query']['orderby'] = 'post__in';

        }
        // END Deal with sticky posts

        $the_query = new WP_Query( $args['query'] );

        // The Loop
        if ( $the_query->have_posts() ) {

            $class_row = ( $args['no-gap'] ) ? ' no-gap-container' : '';
            $ret .= '<div class="row exopite-multifilter-items' . $class_row . '" data-page="' . get_the_permalink() . '">';
            $index = 0;

            if ( $args['except_lenght'] > 0 ) {
                ExopiteSettings::setValue( 'exopite_multifilter_except_lenght', $args['except_lenght'] );
                add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
            }

            if ( ! empty( $args['except_more'] ) && $args['except_more'] != 'none' ) {
                ExopiteSettings::setValue( 'exopite_multifilter_except_more', $args['except_more'] );
                ExopiteSettings::setValue( 'exopite_multifilter_except_more_url', get_the_permalink() );
                add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
            }

            while ( $the_query->have_posts() ) {
                $the_query->the_post();

                $post_password_required = post_password_required();

                $article_thumbnail = '';
                $article_content = '';
                $article_body = '';
                $bootstrap_column_lg = 12 / $args['posts_per_row'];
                $bootstrap_column_md = ( $bootstrap_column_lg == 3 ) ? 4 : 6;
                $classes = ( $args['posts_per_row'] > 1 ) ? 'col-sm-6 col-md-' . $bootstrap_column_md . ' col-lg-' . $bootstrap_column_lg . ' multi-column' : ' single-column';

                if ( $args['posts_per_row'] > 1 ) {
                    $classes = 'col-sm-6 col-md-' . $bootstrap_column_md . ' col-lg-' . $bootstrap_column_lg . ' multi-column';
                    $thumbnail_size = $args['thumbnail-size-multi-row'];
                } else {
                    $classes = 'single-column';
                    $thumbnail_size = $args['thumbnail-size-single-row'];
                }
                $classes .= ( $args['no-gap'] ) ? ' no-gap' : '';


                if ( $args['blog_layout'] == 'left' || ( $args['blog_layout'] == 'zigzag' && ( $index % 2 == 0 ) ) ) {
                    $image_class = 'left';
                } elseif ( $args['blog_layout'] == 'right' || ( $args['blog_layout'] == 'zigzag' && ( $index % 2 == 1 ) ) ) {
                    $image_class = 'right';
                } else {
                    $image_class = $args['blog_layout'];
                }

                if ( $args['blog_layout'] != 'none' ) {
                    $classes .= ( $image_class == 'left' || $image_class == 'right' ) ? ' image-aside' : '';
                    $classes .= ( $args['blog_layout'] != 'none' ) ? ' has-post-thumbnail' : '';
                    $article_thumbnail = $this->get_thumbnail( get_the_id(), $image_class, $thumbnail_size, $args['effect'] );
                }

                if ( ( $args['display_title'] || ( $args['except_lenght'] > 0 ) ) && ! $post_password_required ){

                    $article_content = '<div class="entry-content-container">';
                    if ( $args['display_title'] ) {
                        $article_content .= '<header class="entry-header">';
                        $article_content .= '<h2 class="entry-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
                        //$article_content .= '<div class="entry-meta">META1</div>';
                        $article_content .= '</header>';
                    }
                    if ( $args['except_lenght'] > 0 || $args['except_lenght'] === 'full' ) {
                        $article_content .= '<div class="entry-content">';
                        $article_content .= ($args['except_lenght'] === 'full') ? get_the_content() : get_the_excerpt();
                        $article_content .= '</div>';
                    }
                    $article_content .= '</div>';
                }

                $article_wrapper_begin = '<article class="col-12 ' . $classes . '"><div class="article-container">';

                switch ( $image_class ) {
                    case 'left':
                    case 'top':
                        $article_body = $article_thumbnail . $article_content;
                        break;

                    case 'right':
                        $article_body = $article_content . $article_thumbnail;
                        break;
                }

                $article_wrapper_end = '</div></article>';

                $ret .= $article_wrapper_begin . $article_body . $article_wrapper_end;

                $index++;

            }

            $ret .= '</div>';

            $pagination_orientation = ( $args['pagination'] == 'pagination' ) ? 'text-right' : 'text-center';

            if ( $args['display_pagination'] ) {
                $ret .= '<div class="row exopite-multifilter-paginations ' . $pagination_orientation . '">';

                $ret .= $this->get_pagination( $args['page_id'], $the_query->max_num_pages, $args['paged'], $args['pagination'] );

                $ret .= '</div>';
            }

            /* Restore original Post Data */
            wp_reset_postdata();
            wp_reset_query();
        } else {

            // no posts found
            $ret .= '<div class="col-12 text-center no-posts-found">' . __( 'No posts found', 'exopite-multifilter' ) . '</div>';
        }

        return $ret;

    }

    /**
     * Display shoprt code
     */
    function exopite_multifilter_shortcode( $atts ) {

        // Get atts and defaults.
        $args = shortcode_atts(
            array(
                'post_type'                 => 'post',
                'posts_per_page'            => 4,
                'posts_per_row'             => 2,
                'display_title'             => false,
                'display_pagination'        => true,
                'display_filter'            => true,
                'blog_layout'               => 'top',
                'no-gap'                    => false,
                'except_lenght'             => 0,
                'except_more'               => '',
                'pagination'                => 'pagination',    // pagination, readmore, infinite
                'multi_selectable'          => true,
                'thumbnail-size-single-row' => 'full',
                'thumbnail-size-multi-row'  => 'large',
                'taxonomies_terms'          => 'category',      // term1, term2, ...
                'update_paged'              => false,           // Do not update page in browser URL bar
                'display_page_number'       => false,           // Show page number between loads in infinite and readmore
                'paged'                     => 1,               // Set start page number if not already paged
                'effect'                    => 'apollo',
                'search'                    => '',              // search
                'store_session'             => false,           // store session
                'in_all_taxnomies'          => true,            // positive or negative selection
                'random'                    => false,            // randomize (pagiantion and search are off)
            ),
            $atts
        );

        // Add page id and paged.
        $args['page_id'] = get_the_ID();
        $args['ajax_string'] = bin2hex( mcrypt_create_iv( 10, MCRYPT_DEV_URANDOM ) );
        $args['ajax_nonce'] = wp_create_nonce( $args['ajax_string'] );

        // Do not display filter if it is search
        if ( $args['search'] !== '' ) $args['display_filter'] = false;

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : get_query_var( 'page' );

        $args['paged'] = ( $paged !== 0 ) ? $paged : $args['paged'];

        // create an array from taxonomies_terms, devided by comma.
        $args['taxonomies_terms'] = explode( ',', preg_replace( '/\s+/', '', $args['taxonomies_terms'] ) );

        /*
         * Process terms for taxonomies (if exist)
         * taxonomies_terms="exopite-portfolio-category(suspendisse|tempus), exopite-portfolio-tag"
         */
        foreach ( $args['taxonomies_terms'] as $taxonomy ) {
            if ( strpos( $taxonomy, '(') !== false ) {
                $args['display_filter'] = false;
                preg_match('/\((.*?)\)/s', $taxonomy, $matches, PREG_OFFSET_CAPTURE);
                $terms = explode( '|', $matches[1][0] );
                $taxonomy_name = substr( $taxonomy, 0, strpos( $taxonomy, '(' ) );
                $args['taxonomies'][$taxonomy_name] = $terms;
            } else {
                $args['taxonomies'][$taxonomy] = '';
            }

        }

        $args['taxonomies_terms'] = $args['taxonomies'];

        /*
         * - WRAPPER
         */
        $ret = '<div class="exopite-multifilter-container" data-ajax=\'' . htmlentities( json_encode( $args ), ENT_QUOTES, 'UTF-8' ) . '\'>';

        if ( $args['display_filter'] && ! $args['random'] ) {
            /**
             * Insert args array to data-ajax for javascript as JSON.
             *
             * http://stackoverflow.com/questions/7322682/best-way-to-store-json-in-an-html-attribute
             */
            $ret .= '<div class="exopite-multifilter-filter-wrapper">';
            $ret .= '<div class="exopite-multifilter-filter-reset-search text-right"><span class="exopite-multifilter-filter-reset">' . __( 'Reset all', 'exopite-multifilter' ) . '</span>';

            if ( $args['search'] == '' ) $ret .= '<form role="search" method="get" class="exopite-multifilter-search" action="' . esc_url( home_url( '/' ) ) . '"><div class="form-group"><input type="text" class="form-group" placeholder="' . esc_attr__( 'Search…', 'exopite-multifilter' ) . '" name="s" id="" value="' . esc_attr( get_search_query() ) . '" /><span class="form-group-btn"><button class="btn btn-default" type="submit" id="" value="Search"><i class="fa fa-search" aria-hidden="true"></i></button></span></div><!-- /input-group --></form>';

            $ret .= '</div>';
            $ret .= $this->get_filters( $args['post_type'], $args['taxonomies_terms'], true );
            $ret .= '</div>';
        }

        $ret .= $this->get_articles( $args );
        $ret .= '<div class="exopite-multifilter-now-loading"><div class="uil-ripple-css"><div></div><div></div></div></div>';
        $ret .= '</div>';

        return $ret;
    }

    /**
     * AJAX function to query articles
     */
    function exopite_multifilter_get_posts_callback() {

        $ret = '';

        $AJAX = str_replace( '\"', '"', $_POST["json"] );
        $AJAX = json_decode( $AJAX, true );

        //check_ajax_referer( $AJAX['ajax_string'], $AJAX['ajax_nonce'] );

        $AJAX['paged'] = $AJAX['paged'] ? $AJAX['paged'] : 1;

        $ret .= $this->get_articles( $AJAX );

        die( $ret );

    }

}
