jQuery(document).ready(function ($) {
    let currentIndex = 0;
    const slides = $(".review-slider .slide");
    const slider = $(".review-slider .slider-wrapper");
    let slideWidth = $(".slide").outerWidth(true);
    let visibleSlides = 3; // Show 3 slides at a time
    const totalSlides = slides.length;

    // Duplicate slides for infinite looping
    slider.append(slides.clone()); // Clone all slides at the end
    slider.prepend(slides.clone()); // Clone all slides at the beginning

    // Adjust the starting position to show the first slide properly
    const totalVisibleWidth = slideWidth * visibleSlides;
    slider.css("transform", `translateX(-${totalVisibleWidth}px)`);

    // Function to adjust visibleSlides and slideWidth on window resize
    function adjustSliderSettings() {
        if ($(window).width() <= 768) {
            visibleSlides = 1; // Mobile: 1 slide per view
        } else {
            visibleSlides = 3; // Default: 3 slides per view
        }
        slideWidth = $(".slide").outerWidth(true); // Update slide width
        // Adjust the current index on resize
        slider.css("transform", `translateX(-${(currentIndex + visibleSlides) * slideWidth}px)`);
    }

    // Call adjustSliderSettings on page load and window resize
    adjustSliderSettings();
    $(window).on("resize", function () {
        adjustSliderSettings();
    });

    // Function to update the slider position
    function updateSlider() {
        slider.css("transition", "transform 0.5s ease-in-out");
        slider.css("transform", `translateX(-${(currentIndex + visibleSlides) * slideWidth}px)`);

        // Reset position for seamless looping
        setTimeout(() => {
            if (currentIndex >= totalSlides) {
                currentIndex = 0;
                slider.css("transition", "none");
                slider.css("transform", `translateX(-${visibleSlides * slideWidth}px)`);
            } else if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
                slider.css("transition", "none");
                slider.css("transform", `translateX(-${(currentIndex + visibleSlides) * slideWidth}px)`);
            }
        }, 500); // Match the transition duration
    }

    // Function for Next Button
    $(".next").on("click", function () {
        currentIndex += 1; // Move 1 slide to the right
        updateSlider();
    });

    // Function for Prev Button
    $(".prev").on("click", function () {
        currentIndex -= 1; // Move 1 slide to the left
        updateSlider();
    });

    // Automatic Slider Functionality (every 5000ms)
    setInterval(function () {
        currentIndex += 1; // Move 1 slide to the right automatically
        updateSlider();
    }, 5000); // 5000 milliseconds = 5 seconds
});
