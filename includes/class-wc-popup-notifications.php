<?php
class WC_Popup_Notifications {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'popup_notification_fragment'));
        add_action('woocommerce_add_to_cart', array($this, 'handle_last_added_product'), 10, 6);
    }

    public function enqueue_scripts_and_styles() {
        if ($this->should_display_popup()) {
            wp_enqueue_script('wc-popup-notifications-frontend', plugin_dir_url(__FILE__) . '../assets/js/frontend.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('wc-popup-notifications-frontend', plugin_dir_url(__FILE__) . '../assets/css/frontend.css', array(), '1.0.0');

            $options = get_option('wc_popup_notifications_options');
            $close_after_seconds = apply_filters('wc_popup_notifications_close_after_seconds', $options['close_after_seconds'] ?? 5);
            $top_offset = apply_filters('wc_popup_notifications_top_offset', $options['top_offset'] ?? 20);

            wp_localize_script('wc-popup-notifications-frontend', 'wcPopupNotifications', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wc-popup-nonce'),
                'closeAfterSeconds' => $close_after_seconds,
                'displayPosition' => $options['display_position'] ?? 'bottom',
                'topOffset' => $top_offset,
            ));
        }
    }


	public function enqueue_styles() {
        if ($this->should_display_popup()) {
            wp_enqueue_style('wc-popup-notifications-frontend', plugin_dir_url(__FILE__) . '../assets/css/frontend.css', array(), '1.0.0');
        }
    }


    public function popup_notification_fragment($fragments) {
		if (!$this->should_display_popup()) {
            return $fragments;
        }

		$options = get_option('wc_popup_notifications_options');
		$imagePosition = isset($options['product_image_position']) ? $options['product_image_position'] : 'left';
		$positionClass = isset($options['display_position']) && $options['display_position'] === 'top' ? 'wc-popup-top' : 'wc-popup-bottom';
		$imageAsBackground = isset($options['product_image_background']) && $options['product_image_background'];

		$product_id = WC()->session->get('last_added_product_id');
		$quantity = WC()->session->get('last_added_quantity', 1); // Default to 1 if not set
		$product = wc_get_product($product_id);
		
		if (!$product) return $fragments;
		$imageUrl = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');

		// Start output buffer to capture the HTML
		ob_start();
		?>
		<div id="wc-popup-notification" class="wc-popup-notification <?php echo esc_attr($positionClass); ?>" <?php echo $imageAsBackground ? 'style="background-image: url(' . esc_url($imageUrl) . '); color: #fff;"' : ''; ?>>
			<h4 class="wc-cart-heading">
				Added to Cart
			</h4>
			<?php if (!$imageAsBackground): ?>
				<div class="wc-popup-image" style="float: <?php echo esc_attr($imagePosition); ?>;">
					<img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
				</div>
			<?php endif; ?>
			<div class="wc-popup-content">
				<p class="wc-popup-title"><?php echo esc_html($product->get_name()); ?></p>
				<p class="wc-popup-price">Price: <?php echo wp_kses_post($product->get_price_html()); ?></p>
				<div class="view-cart"><a href="<?php echo wc_get_cart_url() ?>">View Cart</a></div>
			</div>
			<button class="wc-popup-close" style="position: absolute; top: 10px; right: 10px;">&times;</button>
		</div>
		<?php
		$fragments['div.wc-popup-notification'] = ob_get_clean();
		return $fragments;
	}
	
	
	public function handle_last_added_product($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        WC()->session->set('last_added_product_id', $product_id);
        WC()->session->set('last_added_quantity', $quantity);
    }
	
	
	private function should_display_popup() {
        $options = get_option('wc_popup_notifications_options');
        $display_conditions = $options['display_conditions'] ?? [];

        if (in_array('all_pages', $display_conditions)) {
            return true;
        }
        if (in_array('single_products', $display_conditions) && is_product()) {
            return true;
        }
        if (in_array('shop', $display_conditions) && (is_shop() || is_product_category() || is_product_tag())) {
            return true;
        }
        // Extend with more conditions as necessary

        return false;
    }
}