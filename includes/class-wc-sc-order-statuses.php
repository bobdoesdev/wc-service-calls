<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( !class_exists( 'WC_SC_Order_Statuses' ) ) {
	class WC_SC_Order_Statuses{
		public function __construct(){
			add_action( 'init', array( $this, 'register_order_status') );
			add_filter( 'wc_order_statuses', array($this, 'add_order_status') );
			add_action( 'woocommerce_order_status_changed', array( $this, 'send_email' ), 1, 3 );
			$this->set_statuses();
		}

		public $statuses = array();

		public function formatted_status($status){
			return str_replace(' ', '-', strtolower($status));
		}

		public function set_statuses(){
			$this->statuses = array(
					'Awaiting Arrival to Facility',
					'Motor has Arrived',
					'Service Tech Reviewing',
					'Service Tech Extended Review',
					'Repairing Motor',
					'Service Work Complete',
					//'Awaiting Payment for Service',
					'Motor Ready for Pickup',
					'Motor Shipped',
					'Service Closed'
				);
		}

		public function register_order_status(){
			foreach ($this->statuses as $status) {
				$formatted_status = str_replace(' ', '-', strtolower($status));
				register_post_status( 'wc-'.$formatted_status, array(
					'label'						=> $status,
					'public'					=> true,
					//'label_count'				=> _n_noop( 'Motor has arrived at Rhodan <span class="count">(%s)</span>',  'Motor has arrived at Rhodan <span class="count">(%s)</span>' )
					) 
				);
			}
		}

		public function add_order_status( $order_statuses ){
			foreach( $this->statuses as $status  ){
				$order_statuses['wc-' . $this->formatted_status($status)] = $status;
			}
			return $order_statuses;
		}

		public function send_email( $order_id, $old_status, $new_status ){

			global $woocommerce;

			if( $new_status ){

				$order = new WC_Order( $order_id );

				$mailer = WC()->mailer();

				$subject = 'Your status has been updated!';
				$message_body = "<p>Hello again! Your service call status has been updated to:</p>";
				$message_body .= str_replace('-', ' ', strtoupper($new_status) );
				$message = $mailer->wrap_message( $subject, $message_body);
				
				$mailer->send( $order->billing_email, $subject, $message);

			}

		}

	}
	$GLOBALS['wc_sc_order_statuses'] = new WC_SC_Order_Statuses();
}

