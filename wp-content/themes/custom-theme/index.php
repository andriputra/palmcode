<?php
get_header(); 
?>

<main>
    <div class="jumbotron">
        <div class="overlay">
            <div class="content">
                <h1>Sicherheit für Ihr Zuhause und Unternehmen</h1>
                <p>Wir bieten maßgeschneiderte Sicherheitslösungen, um Ihre wertvollsten Güter zu schützen. Vertrauen Sie auf unsere langjährige Erfahrung und modernste Technologie.</p>
                <div class="button-cta">
                    <a href="#Contact">Jetz Anfragen</a>
                </div>
            </div>
        </div>
    </div>

    <section class="service">
        <div class="text-description">
            <div class="title">
                <h1>OUR SERVICES</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <div class="container">
                <div class="content-boxes">
                    <div class="box">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/signal.svg" alt="Einbruchsanlagen Icon" />
                        <div class="desc">
                            <h2>Einbruchsanlagen</h2>
                            <p>Lorem ipsum</p>
                        </div>
                    </div>
                    <div class="box">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/video.svg" alt="Brandmeldeanlagen Icon" />
                        <div class="desc">
                            <h2>Brandmeldeanlagen</h2>
                            <p>Lorem ipsum</p>
                        </div>
                    </div>
                    <div class="box">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/bell.svg" alt="Brandmeldeanlagen Icon" />
                        <div class="desc">
                            <h2>Brandmeldeanlagen</h2>
                            <p>Lorem ipsum</p>
                        </div>
                    </div>
                    <div class="box">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/lock.svg" alt="Rauchabzugsanlagen Icon" />
                        <div class="desc">
                            <h2>Rauchabzugsanlagen</h2>
                            <p>Lorem ipsum</p>
                        </div>
                    </div>
                    <div class="box">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/warning.svg" alt="Brandmeldeanlagen Icon" />
                        <div class="desc">
                            <h2>Lorem Ipsum</h2>
                            <p>Lorem ipsum</p>
                        </div>
                    </div>
                    <div class="box">
                        <h2>Lorem Ipsum</h2>
                        <p class="strong">Jetzt Anfragen ></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-grey">
        <div class="container">
            <div class="about">
                <div class="field one">
                    <div class="title">
                        <h1>Unser<br/>Unternehmen</h1>
                    </div>
                    <p>Die Firma Alarmanlagenbau-Korsing GmbH & Co. KG
                    gibt es seit dem 1.10.1990. Unseren Kunden bieten wir einen 24-Stunden-Service an. Alle Zulassungen, die man für eine erfolgreiche Arbeit auf dem Gebiet der elektronischen Sicherheitstechnik benötigt, sind vorhanden</p>
                </div>
                <div class="field two">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/unser.png" alt="unser" class="img" />
                </div>
            </div>
        </div>
    </section>

    <section class="review">
        <div class="container">
            <div class="review-box">
                <div class="title">
                    <h1 class="uppercase">Was unsere Kunden sagen</h1>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <?php echo do_shortcode('[review_slider]'); ?>
        </div>
    </section>

    <section class="contact" id="Contact">
        <div class="container">
            <div class="bg-grey">
                <div class="contact-form-wrapper">
                    <h2>Kontaktformular</h2>
                    <!-- <form id="contactForm" class="contact-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Name*" required>
                            <input type="email" name="email" placeholder="E-Mail*" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" placeholder="Telefonnummer*" required>
                            <select name="services" required>
                                <option value="" disabled selected>Services*</option>
                                <option value="Service 1">Service 1</option>
                                <option value="Service 2">Service 2</option>
                                <option value="Service 3">Service 3</option>
                            </select>
                        </div>
                        <textarea name="message" placeholder="Nachricht"></textarea>
                        <div class="file-upload">
                            <label for="file">
                                Dateien hochladen (nur Bilddateien erlaubt)
                                <input type="file" id="file" name="attachment" accept="image/*">
                            </label>
                            <div id="fileNameDisplay" style="margin-top: 10px; font-style: italic; color: #333;"></div>
                        </div>
                        <p class="disclaimer">
                            Mit (<span class="required">*</span>) markierte Felder sind Pflichtfelder.<br>
                            Mit dem Absenden bestätige ich die <a href="#" target="_blank">Datenschutzinformation</a> gelesen zu haben und bestätige diese.
                        </p>
                        <div id="formMessage"></div>
                        <button type="submit" class="button-cta">JETZ ANFRAGEN</button>
                    </form> -->
                    <?php echo do_shortcode('[custom_contact_form]'); ?>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php
get_footer(); 
?>