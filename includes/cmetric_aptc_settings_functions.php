<?php
// class for custom tab shipping by city woocommerce settings
if ( ! class_exists( 'WP_Class_Cmetric_Aptc_Settings' ) ) :

class WP_Class_Cmetric_Aptc_Settings  {

    /**
     * Setup settings class
     *
     * @since  1.0
     */
      public function __construct() {
      
        $this->id    = 'aptc';
        $this->label = __( 'Advance Shipping Zone', 'cmetric-aptc' );
        
              /* common hooks for custom tab field setting WooCommerce */
                            
                add_filter( 'woocommerce_product_data_tabs', array($this, 'add_my_add_product_to_cart_data_tab') , 99 , 1 );
                add_action( 'woocommerce_product_data_panels', array($this, 'add_my_add_product_to_cart_data_fields') );
                add_action( 'woocommerce_process_product_meta', array($this, 'woocommerce_process_product_meta_fields_save' ));
                add_action( 'template_redirect', array($this, 'cmetric_aptc_add_product_to_cart' ));           

      }
    
        /**
         * Add the custom tab WooCommerce
         */
        public function add_my_add_product_to_cart_data_tab( $product_data_tabs ) {
                $product_data_tabs['add-product-to-cart-custom-tab'] = array(
                    'label' => __( 'Add Product To Cart', 'add_product_to_cart_text_domain' ),
                    'target' => 'my_custom_add_product_to_cart_data',
                );
                return $product_data_tabs;
        }
          
        /**
         * Function that displays output for the shipping tab.
         */

        public function add_my_add_product_to_cart_data_fields() {
            global $woocommerce, $post;
            ?>
            <!-- id below must match target registered in above add_my_add_product_to_cart_data_tab function -->
            <div id="my_custom_add_product_to_cart_data" class="panel woocommerce_options_panel">
                <?php
                woocommerce_wp_checkbox( array( 
                    'id'            => '_my_custom_add_product_to_cart', 
                    'wrapper_class' => 'show_if_simple', 
                    'label'         => __( 'Allow this product to cart', 'add_product_to_cart_text_domain' ),
                    'description'   => __( 'Enable option to automatically add product to cart on site visit', 'add_product_to_cart_text_domain' ),
                    'default'       => '0',
                    'desc_tip'      => false,
                ) );
                ?>
            </div>
            <?php
        }

        /*
         * Save our custom product fields
         */

        public function woocommerce_process_product_meta_fields_save( $post_id ){
            // This is the case to save custom field data of checkbox. You have to do it as per your custom fields
            $my_custom_add_product_to_cart = sanitize_text_field($_POST['_my_custom_add_product_to_cart']);
			$woo_checkbox = isset( $my_custom_add_product_to_cart ) ? 'yes' : 'no';
            update_post_meta( $post_id, '_my_custom_add_product_to_cart', $woo_checkbox );
        }

        /**
         * Automatically add product to cart on visit
         */
        
        public function cmetric_aptc_add_product_to_cart() {
            if ( ! is_admin() ) {
                    
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1,
                             'meta_key' => '_my_custom_add_product_to_cart',
                             'meta_value' => 'yes',            
                        );

                        $loop = new WP_Query( $args );
                        $prod_ids = array();

                        while ( $loop->have_posts() ) : $loop->the_post();
                            global $product;
                           $prod_ids[] = get_the_ID();
                           
                        endwhile;

                        wp_reset_query();
            
                    $articles = $prod_ids; // assign product array to articles array
                    $found = false;

                    // check if product already in cart
                    if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                            $_product = $values['data'];

                            if (($key = array_search($_product->id, $articles)) !== false)
                                unset($articles[$key]);
                        }

                        // if product not found, add it
                        if ( count($articles) > 0 ) {
                            foreach ($articles as $article) {
                                WC()->cart->add_to_cart($article);
                            }
                        }
                    } else {
                        // if no products in cart, add it
                        foreach ($articles as $article) {
                            WC()->cart->add_to_cart( $article );
                        }
                    }
            }
        }

         
 
       
}
endif;
?>