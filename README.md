# WooCommerce Popup Notifications

## Description

WooCommerce Popup Notifications is a WordPress plugin designed to enhance the shopping experience on WooCommerce-powered websites. It displays a notification in a popup whenever a product is added to the cart, providing immediate feedback to the users about their action. The plugin is built with flexibility in mind, allowing for customization to fit various themes and preferences.

## Features

- **Customizable Popup Notifications**: Displays an add-to-cart notification with product details in a visually appealing popup.
- **Adaptive Display Positions**: Offers options to display the popup at the top or bottom of the page, with additional customization for positioning.
- **Product Image Handling**: Allows displaying the product image within the popup content or as a background image for a more immersive feel.
- **Extensible through Filters**: Provides several filters for developers to customize the popup's behavior, including auto-close duration and display conditions.

## Classes

### `WC_Popup_Notifications`

The main class responsible for initializing the plugin's functionality. It handles the enqueuing of scripts and styles, generates the popup notification fragment, and listens for add-to-cart actions.

- **Methods**:
  - `enqueue_scripts_and_styles()`: Enqueues necessary JavaScript and CSS files.
  - `popup_notification_fragment()`: Generates the HTML fragment for the popup notification.
  - `track_last_added_product()`: Tracks the last product added to the cart for displaying in the popup.
  - `should_display_popup()`: Determines whether the popup should be displayed based on admin-defined conditions.

### `WC_Popup_Notifications_Admin`

Handles the backend configuration for the plugin, allowing administrators to customize the popup notification settings.

- **Note**: This class and its functionality might need to be defined based on your plugin's admin settings requirements.

## Filters

- `wc_popup_notifications_close_after_seconds`: Allows changing the duration (in seconds) after which the popup notification will automatically close. Default is 5 seconds.

  ```php
  add_filter('wc_popup_notifications_close_after_seconds', function($seconds) {
      return 10; // Change the popup auto-close duration to 10 seconds.
  });
  ```

- `wc_popup_notifications_top_offset`: Customizes the top offset for the popup when displayed at the top of the page. Default is 20px.

  ```php
  add_filter('wc_popup_notifications_top_offset', function($offset) {
      return 50; // Set the top offset to 50px.
  });
  ```

- **Display Conditions**: While not directly a filter, the plugin provides a mechanism to define conditions under which the popup should be shown (e.g., on all pages, single product pages, etc.). This is configurable through the plugin's settings in the admin area.

## Installation
1. Upload the plugin files to the `/wp-content/plugins/woocommerce-popup-notifications` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the plugin's settings page to configure the plugin (if available).

## Usage
Once activated, the plugin works automatically by displaying a popup notification whenever a product is added to the cart. Administrators can customize the plugin's behavior through the WordPress admin panel under the plugin's settings section.

## License
This plugin is licensed under the [GPLv2 (or later)](https://www.gnu.org/licenses/gpl-2.0.html).

## Author
Hassan Ejaz  
[https://brandbees.net](https://brandbees.net)
