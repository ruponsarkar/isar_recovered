<p><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <span style="white-space: pre-line">{{ $contactDetails['address'] }}</span></p>
<p><i class="fa-solid fa-envelope" aria-hidden="true"></i> <a class="d-inline" href="mailto:{{ $contactDetails['email'] }}">{{ $contactDetails['email'] }}</a></p>
<p><i class="fa-solid fa-phone" aria-hidden="true"></i> <a class="d-inline" href="tel:{{ preg_replace('/[^+0-9]/', '', $contactDetails['phone']) }}">{{ $contactDetails['phone'] }}</a></p>
