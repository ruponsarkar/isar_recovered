<footer class="footer mt-4">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-4"><img src="{{ backend_asset_url('mainlogo.png') }}" class="footer-logo" alt="ISAR">
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
                {{-- <a href="/PublicationFee">Publication Fee</a> --}}
                <a href="{{ route('contact', ['journal' => data_get($journal ?? null, 'slug') ?: data_get($journal ?? null, 'j_id')]) }}">Contact Us</a>
            </div>
            <div class="col-lg-4">
                <h6>Contact</h6>
                @include('partials.contact-details')
            </div>
        </div>
        <hr>
        <p class="small mb-0">
            <img alt="Creative Commons License" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" style="border-width: 0px;">
            This work is licensed under a <a class="d-inline" href="https://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a></p>
    </div>
    <div class="copyright">
        @if ($journalName = data_get($journal ?? null, 'j_name'))
            © {{ date('Y') }} | {{ $journalName }}. Published by ISAR Publisher.
        @else
            © {{ date('Y') }} | ISAR Publisher
        @endif
        Developed by <a class="d-inline" href="https://pageuptechnologies.com/">PageUp Technologies</a>
    </div>
</footer>
