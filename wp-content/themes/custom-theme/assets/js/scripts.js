


jQuery(document).ready(function ($) {
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();

        const targetId = $(this).attr('href');
        const targetElement = $(targetId);

        if (targetElement.length) {
            $('html, body').animate(
                {
                    scrollTop: targetElement.offset().top,
                },
                800
            );
        }
    });
});


document.getElementById("file").addEventListener("change", function(event) {
    var fileInput = event.target;
    var fileNameDisplay = document.getElementById("fileNameDisplay");
    
    // If a file is selected, display its name
    if (fileInput.files.length > 0) {
        var fileName = fileInput.files[0].name;
        fileNameDisplay.textContent = "Ausgewähltes Bild: " + fileName; // Display the file name
    } else {
        fileNameDisplay.textContent = ""; // Clear if no file is selected
    }
});