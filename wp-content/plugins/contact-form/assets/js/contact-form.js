jQuery(document).ready(function ($) {
    $("#contactForm").on("submit", function (e) {
        e.preventDefault();

        // Reset form message
        $("#formMessage").html('');

        // Prepare Form Data
        let formData = new FormData(this);
        formData.append('nonce', $('input[name="nonce"]').val());

        // AJAX Request
        $.ajax({
            url: myAjax.ajax_url, 
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $("#formMessage").html('<p style="color: green;">' + response.data.message + '</p>');
                    $("#contactForm")[0].reset(); // Reset form on success
                } else {
                    let errorMessage = response.data?.message || "Es ist ein Fehler aufgetreten.";
                    $("#formMessage").html('<p style="color: red;">' + errorMessage + '</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                $("#formMessage").html('<p style="color: red;">Etwas ist schief gelaufen. Bitte versuchen Sie es erneut.</p>');
            },
        });
    });
});
