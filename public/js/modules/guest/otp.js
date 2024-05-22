/**
 * OTP Form Handling
 * This script enhances the functionality of OTP (One-Time Password) input forms.
 * It manages input focus, keyboard navigation, and pasting OTP from clipboard.
 * @listens document.ready - Event triggered when the DOM is fully loaded.
 */
$(document).ready(function () {
    // Iterate over each OTP form on the page
    $('form.otp-form').each(function () {
        const form = $(this); // Get the current form
        const inputs = form.find('input.otp-input'); // Find all OTP input fields

        // Set focus on the first OTP input field
        inputs.eq(0).focus();

        // Add event listeners to all OTP input fields for handling input
        inputs.on("input", function (event) {
            const value = $(this).val();
            if (value.length === 1) {
                const index = inputs.index(this);
                if (index < inputs.length - 1) {
                    // Move focus to the next input field if available
                    $(inputs[index + 1]).focus();
                } 
            }
        });

        // Add event listeners to all OTP input fields for handling keyboard navigation
        inputs.on("keydown", function (event) {
            const value = $(this).val();
            if (event.key === "Backspace" && value.length === 0) {
                const index = inputs.index(this);
                if (index > 0) {
                    // Move focus to the previous input field if available
                    $(inputs[index - 1]).focus();
                }
            }
        });

        // Add event listeners to all OTP input fields for handling keyup events
        inputs.on("keyup", function (event) {
            const value = $(this).val();
            if (value.length > 1 && $(this).is('input.otp-input:last')) {
                $(this).blur(); // Blur the input field if more than one character is entered and it's the last field
            }
        });

        // Add event listeners to all OTP input fields for handling focus
        inputs.on('focus', function(event){
            $(this).val(''); // Clear the value of the input field on focus
        });

        // Handle pasting OTP from clipboard
        form.on("paste", function (event) {
            event.preventDefault();
            const pastedData = event.originalEvent.clipboardData.getData("text");
            if (pastedData.length === inputs.length) {
                // Paste OTP characters into respective input fields
                inputs.each(function (index) {
                    $(this).val(pastedData[index]);
                    if (index < inputs.length - 1) {
                        $(this).trigger("input");
                    }
                    if (index === 5) {
                        form.submit(); // Submit the form after pasting all OTP characters
                    }
                });
            }
        });
    });
});
