jQuery(document).ready(function($) {
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash) {
        // Append or replace the popup HTML fragment
        if ($('#wc-popup-notification').length) {
            $('#wc-popup-notification').replaceWith(fragments['div.wc-popup-notification']);
        } else {
            $('body').append(fragments['div.wc-popup-notification']);
        }

        setTimeout(function() {
            if ($('#wc-popup-notification').hasClass('wc-popup-top')) {
                var headerHeight = $('header').outerHeight(true) || 0;
                var topOffset = 20;
                
                $('#wc-popup-notification').css({
                    'top': headerHeight + topOffset + 'px',
                    'right': '-100%', // Start off-screen to the right
                    'bottom': 'auto'
                });
            } else {
                $('#wc-popup-notification').css({
                    'bottom': '20px',
                    'right': '-100%', // Start off-screen to the right
                    'top': 'auto'
                });
            }

            // Slide in by animating the right property
            $('#wc-popup-notification').show().animate({
                'right': '20px' // Adjust as needed to match your design
            }, 500); // Duration of the slide-in animation
        }, 20);

        // Attach an event handler for the close button
        $(document).on('click', '#wc-popup-notification .wc-popup-close', function() {
            // Slide out by animating the right property back
            $('#wc-popup-notification').animate({
                'right': '-100%' // Move it back off-screen to the right
            }, 500, function() {
                $(this).hide(); // Hide after animation completes
            });
        });

        // Auto-close the popup after the specified delay
        setTimeout(function() {
            $('#wc-popup-notification').fadeOut();
        }, wcPopupNotifications.closeAfterSeconds * 1000); // Convert seconds to milliseconds
    });
});