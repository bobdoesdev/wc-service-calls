<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( !class_exists( 'WC_SC_Checkout_Fields' ) ) {
	class WC_SC_Checkout_Fields{
		public function __construct(){
			add_action( 'woocommerce_after_order_notes', array( $this, 'add_checkout_fields') );
			add_action( 'woocommerce_checkout_process', array( $this, 'process_checkout_fields' ) );
			add_action('woocommerce_checkout_update_order_meta', array($this, 'update_data') );
			add_action( 'woocommerce_admin_order_data_after_billing_address', array($this, 'display_admin_order_meta') );
		}

		public $show_fields;

		public function add_checkout_fields( $checkout ) {

			$this->show_fields = false;

			$cart = WC()->cart->get_cart();
			foreach ($cart as $cart_item_key => $cart_item ) {
				$product = $cart_item['data'];
			   if ( has_term( 'service-request', 'product_cat', $product->id ) ) {
			       $this->show_fields = true;
			       break;
			   }
			}

			if ($this->show_fields) {
			
			    echo '<div id="service-request-details"><h3>' . __('Service Request Details') . '</h3>';

			    woocommerce_form_field( 'service_request_use', array(
			        'type'          => 'text',
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Use'),
			        'placeholder'   => __('Recreation, Guide, Dealer'),
			        'required'		=> true,
			        ), $checkout->get_value( 'service_request_use' ));

			    woocommerce_form_field( 'motor_serial_number', array(
			        'type'          => 'text',
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Motor Serial Number'),
			        'placeholder'   => __(''),
			        'required'		=> true,
			        ), $checkout->get_value( 'motor_serial_number' ));

			    woocommerce_form_field( 'purchase_date', array(
			        'type'          => 'date',
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Purchase Date'),
			        'placeholder'   => __(''),
			        'required'		=> true,
			        ), $checkout->get_value( 'purchase_date' ));

			    woocommerce_form_field( 'purchase_location', array(
			        'type'          => 'text',
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Purchase Location'),
			        'placeholder'   => __(''),
			        'required'		=> true,
			        ), $checkout->get_value( 'purchase_location' ));

			    woocommerce_form_field( 'warranty', array(
			        'type'          => 'select',
			        'options'		=> array(
			        		'Yes' => 'Yes',
			        		'No'  => 'No'
			        	),
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Warranty'),
			        'placeholder'   => __(''),
			        'required'		=> true,
			        ), $checkout->get_value( 'warranty' ));

			    woocommerce_form_field( 'service_reason', array(
			        'type'          => 'textarea',
			        'class'         => array('service-request-form-field form-row-wide'),
			        'label'         => __('Reason for Service'),
			        'placeholder'   => __(''),
			        'required'		=> true,
			        ), $checkout->get_value( 'service_reason' ));

			    echo '<p><strong>Note: Please ensure the FOB is returned with the motor for repair</p></strong>';

			    echo '</div>';

			}

		}

		public function process_checkout_fields() {

			if ($this->show_fields) {
				if ( !$_POST['service_request_use'] || !$_POST['motor_serial_number'] || !$_POST['purchase_date'] || !$_POST['purchase_location'] || !$_POST['warranty'] || !$_POST['service_reason'] )
				    wc_add_notice( __( 'Please fill out all Service Request Fields' ), 'error' );
			}

		}

		public function update_data( $order_id  ){

			if ( ! empty( $_POST['service_request_use'] ) ) {
			    update_post_meta( $order_id, 'service_request_use', sanitize_text_field( $_POST['service_request_use'] ) );
			}

			if ( ! empty( $_POST['motor_serial_number'] ) ) {
			    update_post_meta( $order_id, 'service_request_motor_serial_number', sanitize_text_field( $_POST['motor_serial_number'] ) );
			}

			if ( ! empty( $_POST['purchase_date'] ) ) {
			    update_post_meta( $order_id, 'service_request_purchase_date', sanitize_text_field( $_POST['purchase_date'] ) );
			}

			if ( ! empty( $_POST['purchase_location'] ) ) {
			    update_post_meta( $order_id, 'service_request_purchase_location', sanitize_text_field( $_POST['purchase_location'] ) );
			}

			if ( ! empty( $_POST['warranty'] ) ) {
			    update_post_meta( $order_id, 'service_request_warranty', sanitize_text_field( $_POST['warranty'] ) );
			}

			if ( ! empty( $_POST['service_reason'] ) ) {
			    update_post_meta( $order_id, 'service_request_reason', sanitize_text_field( $_POST['service_reason'] ) );
			}

		}

		public function display_admin_order_meta( $order ){

			$items = $order->get_items();
			foreach ($items as $item) {
			   if ( has_term( 'service-request', 'product_cat', $item->get_product_id() ) ) {
			   		echo '<h3>Service Request Information</h3>';
			   	    echo '<strong>'.__('Use').':</strong> ' . get_post_meta( $order->id, 'service_request_use', true ) . '</br>';
			   	    echo '<strong>'.__('Motor Serial Number').':</strong> ' . get_post_meta( $order->id, 'service_request_motor_serial_number', true ) . '</br>';
			   	    echo '<strong>'.__('Purchase Date').':</strong> ' . get_post_meta( $order->id, 'service_request_purchase_date', true ) . '</br>';
			   	    echo '<strong>'.__('Purchase Location').':</strong> ' . get_post_meta( $order->id, 'service_request_purchase_location', true ) . '</br>';
			   	    echo '<strong>'.__('Warranty').':</strong> ' . get_post_meta( $order->id, 'service_request_warranty', true ) . '</br>';
			   	    echo '<strong>'.__('Reason for Request').':</strong> ' . get_post_meta( $order->id, 'service_request_reason', true ) . '</p>';
			   } 
			}			
		}
	}

	$GLOBALS['wc_sc_checkout_fields'] = new WC_SC_Checkout_Fields();
}

