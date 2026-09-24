<footer class="footer mt-4">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-4"><img src="{{ asset('assets/mainlogo.png') }}" class="footer-logo" alt="ISAR">
                <p>International Scientific and Academic Research Publisher</p>
                <div class="footer-social"><a href="https://www.facebook.com/profile.php?id=61552592232822"><i
                            class="fa-brands fa-facebook"></i></a><a href="https://twitter.com/ISARPublisher"><i
                            class="fa-brands fa-x-twitter"></i></a><a href="https://www.instagram.com/isarpublisher/"><i
                            class="fa-brands fa-instagram"></i></a><a
                        href="https://www.linkedin.com/company/isar-publisher/"><i
                            class="fa-brands fa-linkedin"></i></a></div>
            </div>
            <div class="col-lg-4">
                <h6>Useful Links</h6>
                <a href="/AllJournals">Journals</a>
                <a href="/aboutUs">About Us</a>
                <a href="/PublicationFee">Publication Fee</a>
                <a href="/action">Contact Us</a>
            </div>
            <div class="col-lg-4">
                <h6>Contact</h6>
                <p><i class="fa-solid fa-location-dot"></i> H.No. 61, Ward No. 4, Bokakhat, Golaghat, Assam, India,
                    785612</p>
                <p><i class="fa-solid fa-envelope"></i> <a class="d-inline"
                        href="mailto:contact@isarpublisher.com">contact@isarpublisher.com</a></p>
                <p><i class="fa-solid fa-phone"></i> <a class="d-inline" href="tel:+918638994354">+91 8638994354</a></p>
            </div>
        </div>
        <hr>
        <p class="small mb-0">Except where otherwise noted, content is licensed under a Creative Commons
            Attribution-NonCommercial 4.0 International License (CC BY-NC 4.0).</p>
    </div>
    <div class="copyright">
        @if ($journalName = data_get($journal ?? null, 'j_name'))
            © {{ $journalName }}. All Rights Reserved.
        @else
            © {{ date('Y') }} ISAR Publisher. All rights reserved.
        @endif
        Developed by <a class="d-inline" href="https://pageuptechnologies.com/">PageUp Technologies</a>
    </div>
</footer>
