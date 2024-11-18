jQuery(document).ready(function ($) {
    let currentIndex = 0;
    const slides = $(".slide");
    const slider = $(".slider-wrapper");
    const slideWidth = $(".slide").outerWidth(true);
    const visibleSlides = 3; // 3 items per slide
    const totalSlides = slides.length;

    // Duplicate slides for infinite looping
    slider.append(slides.clone()); // Clone all slides
    slider.prepend(slides.clone()); // Prepend all slides

    // Adjust starting position to show first original slide
    const totalVisibleWidth = slideWidth * totalSlides;
    slider.css("transform", `translateX(-${totalVisibleWidth}px)`);

    function updateSlider() {
        slider.css("transition", "transform 0.5s ease-in-out");
        slider.css(
            "transform",
            `translateX(-${(currentIndex + totalSlides) * slideWidth}px)`
        );

        // Reset position for seamless looping
        setTimeout(() => {
            if (currentIndex >= totalSlides) {
                currentIndex = 0;
                slider.css("transition", "none");
                slider.css(
                    "transform",
                    `translateX(-${totalSlides * slideWidth}px)`
                );
            } else if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
                slider.css("transition", "none");
                slider.css(
                    "transform",
                    `translateX(-${(currentIndex + totalSlides) * slideWidth}px)`
                );
            }
        }, 500); // Match the transition duration
    }

    $(".prev").on("click", function () {
        currentIndex -= 1; // Move 1 item left
        updateSlider();
    });

    $(".next").on("click", function () {
        currentIndex += 1; // Move 1 item right
        updateSlider();
    });
});
