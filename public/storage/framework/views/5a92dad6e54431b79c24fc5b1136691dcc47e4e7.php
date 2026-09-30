<header>
    <div class="social-strip"><a aria-label="WhatsApp" href="https://wa.me/918638994354"><i
                class="fa-brands fa-whatsapp"></i></a><a aria-label="Facebook"
            href="https://www.facebook.com/profile.php?id=61552592232822"><i class="fa-brands fa-facebook"></i></a><a
            aria-label="Instagram" href="https://www.instagram.com/isarpublisher/"><i
                class="fa-brands fa-instagram"></i></a></div>
    <div class="masthead"><a href="<?php echo e(route('home')); ?>" aria-label="ISAR Publisher home"><img src="<?php echo e(asset('assets/mainlogo.png')); ?>" alt="ISAR Publisher"></a>
        <h1>International Scientific and Academic Research Publisher</h1>
    </div>
    <?php if(isset($journalPages)): ?>
        <?php echo $__env->make('partials.journal-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <nav class="navbar navbar-expand-lg navbar-dark isar-nav sticky-top">
            <div class="container-fluid"><a class="navbar-brand" href="/">ISAR Publisher</a><button
                    class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/AllJournals">Journals</a></li>
                        
                        
                        
                        <li class="nav-item"><a class="nav-link" href="/aboutUs">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="/PublicationFee">Publication Fee</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Payment">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="/action">Contact Us</a></li>
                        <li class="nav-item mobile-submit"><a class="nav-link" href="<?php echo e(route('manuscript', ['journal' => data_get($journal ?? null, 'slug') ?: data_get($journal ?? null, 'j_id')])); ?>">Submit Manuscript</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>
</header>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/partials/nav.blade.php ENDPATH**/ ?>