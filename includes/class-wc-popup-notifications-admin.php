<?php
class WC_Popup_Notifications_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts')); // Enqueue the admin scripts
    }
	
	public function enqueue_admin_scripts() {
        wp_enqueue_script(
            'wc-popup-notifications-admin', // Handle for the script.
            plugin_dir_url(__FILE__) . '../assets/js/admin.js', // Path to the script file, relative to the current file.
            array('jquery'), // Script dependencies. Assuming you're using jQuery.
            '1.0.0', // Version number for the script.
            true // Specify whether to enqueue the script in the footer.
        );
    }

    public function add_plugin_page() {
        // Add the menu page for the plugin settings
        add_menu_page(
            'WooCommerce Popup Notifications Settings', // Page title
            'WC Popup', // Menu title
            'manage_options', // Capability
            'wc-popup-notifications', // Menu slug
            array($this, 'create_admin_page'), // Function to display the settings page
            'dashicons-megaphone', // Icon URL
            6 // Position
        );
    }

    public function create_admin_page() {
        ?>
        <div class="wrap">
            <h2>WooCommerce Popup Notifications Settings</h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('wc_popup_notifications_group');
                do_settings_sections('wc-popup-notifications');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        register_setting(
            'wc_popup_notifications_group', // Option group
            'wc_popup_notifications_options', // Option name
            array($this, 'sanitize') // Sanitize callback
        );

        // Add settings section
        add_settings_section(
            'wc_popup_notifications_setting_section', // ID
            'Settings', // Title
            null, // Callback
            'wc-popup-notifications' // Page
        );

        // Add settings fields
        $this->add_settings_fields();
    }

    private function add_settings_fields() {
        // Product image position within the content
        add_settings_field(
            'product_image_position', // ID
            'Product Image Position', // Title
            array($this, 'product_image_position_callback'), // Callback
            'wc-popup-notifications', // Page
            'wc_popup_notifications_setting_section', // Section
            array('label_for' => 'product_image_position')
        );

        // Product image as a background
        add_settings_field(
            'product_image_background', // ID
            'Product Image as Background', // Title
            array($this, 'product_image_background_callback'), // Callback
            'wc-popup-notifications', // Page
            'wc_popup_notifications_setting_section', // Section
            array('label_for' => 'product_image_background')
        );

        // Display position
        add_settings_field(
            'display_position', // ID
            'Display Position', // Title
            array($this, 'display_position_callback'), // Callback
            'wc-popup-notifications', // Page
            'wc_popup_notifications_setting_section', // Section
            array('label_for' => 'display_position')
        );

        // Close after seconds
        add_settings_field(
            'close_after_seconds', // ID
            'Close After Seconds', // Title
            array($this, 'close_after_seconds_callback'), // Callback
            'wc-popup-notifications', // Page
            'wc_popup_notifications_setting_section', // Section
            array('label_for' => 'close_after_seconds')
        );
		
		// Add display conditions settings field
		add_settings_field(
			'display_conditions', // ID
			'Display Conditions', // Title
			array($this, 'display_conditions_callback'), // Callback
			'wc-popup-notifications', // Page
			'wc_popup_notifications_setting_section', // Section
			array('label_for' => 'display_conditions')
		);

    }

    public function sanitize($input) {
		$new_input = array();

		if(isset($input['product_image_position'])) {
			$new_input['product_image_position'] = sanitize_text_field($input['product_image_position']);
		}

		if(isset($input['product_image_background'])) {
			$new_input['product_image_background'] = sanitize_text_field($input['product_image_background']);
		}

		if(isset($input['display_position'])) {
			$new_input['display_position'] = sanitize_text_field($input['display_position']);
		}

		if(isset($input['close_after_seconds'])) {
			$new_input['close_after_seconds'] = absint($input['close_after_seconds']);
		}
		
		
		if (isset($input['display_conditions'])) {
			$conditions = explode(',', $input['display_conditions']);
			$conditions = array_filter($conditions); // Remove empty elements
			$conditions = array_unique($conditions); // Remove duplicates
			$new_input['display_conditions'] = array_map('sanitize_text_field', $conditions);
		}


		return $new_input;
	}


    // Callbacks for each setting field to render the UI
    public function product_image_position_callback() {
		$options = get_option('wc_popup_notifications_options');
		?>
		<select id="product_image_position" name="wc_popup_notifications_options[product_image_position]">
			<option value="left" <?php selected(isset($options['product_image_position']) ? $options['product_image_position'] : '', 'left'); ?>>Left</option>
			<option value="right" <?php selected(isset($options['product_image_position']) ? $options['product_image_position'] : '', 'right'); ?>>Right</option>
		</select>
		<p class="description">Choose whether the product image should appear on the left or right side within the content of the notification.</p>
		<?php
	}


    public function product_image_background_callback() {
		$options = get_option('wc_popup_notifications_options');
		?>
		<input type="checkbox" id="product_image_background" name="wc_popup_notifications_options[product_image_background]" value="1" <?php checked(isset($options['product_image_background']) ? $options['product_image_background'] : 0, 1); ?> />
		<label for="product_image_background">Use the product image as the background of the notification.</label>
		<?php
	}


	public function display_position_callback() {
		$options = get_option('wc_popup_notifications_options');
		?>
		<select id="display_position" name="wc_popup_notifications_options[display_position]">
			<option value="top" <?php selected(isset($options['display_position']) ? $options['display_position'] : '', 'top'); ?>>Top</option>
			<option value="bottom" <?php selected(isset($options['display_position']) ? $options['display_position'] : '', 'bottom'); ?>>Bottom</option>
		</select>
		<p class="description">Select whether the notification should appear at the top or bottom of the page. The notification will always be shown on the right side.</p>
		<?php
	}


    public function close_after_seconds_callback() {
		$options = get_option('wc_popup_notifications_options');
		?>
		<input type="number" id="close_after_seconds" name="wc_popup_notifications_options[close_after_seconds]" value="<?php echo isset($options['close_after_seconds']) ? esc_attr($options['close_after_seconds']) : ''; ?>" min="1" />
		<p class="description">Define the number of seconds after which the notification will be automatically closed.</p>
		<?php
	}
	
	public function display_conditions_callback() {
		$options = get_option('wc_popup_notifications_options');
		$conditions = isset($options['display_conditions']) ? $options['display_conditions'] : [];

		// Define the full list of conditions
		$all_conditions = [
			'all_pages' => 'All Pages',
			'shop_archive' => 'Shop Archive',
			'shop_archive_categories' => 'Shop Archive Categories',
			'shop_archive_tags' => 'Shop Archive Tags',
			'shop_archive_attributes' => 'Shop Archive Product Attributes',
			'single_products' => 'Single Products',
		];

		// Render the dropdown for adding new conditions
		echo '<select id="wc-popup-new-condition">';
		echo '<option value="">Select a condition</option>';
		foreach ($all_conditions as $key => $label) {
			echo '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
		}
		echo '</select>';
		echo '<button type="button" class="button wc-popup-add-condition">Add Display Condition</button>';

		// Render the current conditions list
		echo '<ul class="wc-popup-conditions-list">';
		foreach ($conditions as $condition) {
			// Only display conditions that are still valid (exist in $all_conditions)
			if (array_key_exists($condition, $all_conditions)) {
				echo '<li data-condition="' . esc_attr($condition) . '">' . esc_html($all_conditions[$condition]) . '<button type="button" class="wc-popup-remove-condition" style="color: red; margin-left: 10px;">&times;</button></li>';
			}
		}
		echo '</ul>';

		// Hidden input for storing the conditions
		echo '<input type="hidden" name="wc_popup_notifications_options[display_conditions]" class="wc-popup-conditions-hidden" value="' . esc_attr(implode(',', $conditions)) . '">';
	}
}