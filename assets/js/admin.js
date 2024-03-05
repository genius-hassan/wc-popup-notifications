jQuery(document).ready(function($) {
    function updateDropdown() {
        $('#wc-popup-new-condition option').show(); // Show all options initially

        // Get all selected conditions
        var selectedConditions = $('.wc-popup-conditions-list li').map(function() {
            return $(this).data('condition');
        }).get();

        // Hide options in the dropdown that have been selected
        $('#wc-popup-new-condition option').each(function() {
            if ($.inArray($(this).val(), selectedConditions) !== -1) {
                $(this).hide();
            }
        });
    }

    // Update the hidden input and the dropdown after adding or removing conditions
    function updateHiddenInputAndDropdown() {
        var conditions = $('.wc-popup-conditions-list li').map(function() {
            return $(this).data('condition');
        }).get();
        $('.wc-popup-conditions-hidden').val(conditions.join(','));

        updateDropdown(); // Update the dropdown to reflect the current selections
        $('#wc-popup-new-condition').val(''); // Unset the dropdown after adding a condition
    }

    $(document).on('click', '.wc-popup-add-condition', function() {
        var condition = $('#wc-popup-new-condition').val();
        var conditionText = $('#wc-popup-new-condition option:selected').text();
        if (condition) {
            $('.wc-popup-conditions-list').append('<li data-condition="' + condition + '">' + conditionText + '<button type="button" class="wc-popup-remove-condition" style="color: red; margin-left: 10px;">&times;</button></li>');
            updateHiddenInputAndDropdown();
        }
    });

    $(document).on('click', '.wc-popup-remove-condition', function() {
        $(this).parent().remove();
        updateHiddenInputAndDropdown();
    });

    // Initial update to hide already selected options
    updateDropdown();
});
