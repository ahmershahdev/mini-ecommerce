<footer class="site-footer">
    <div class="container py-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <h5 class="footer-title mb-1">Commerza</h5>
                <p class="mb-1 small">Premium mini ecommerce demo with Bootstrap, jQuery, AJAX, PHP, SQL and SEO.</p>
                <p class="mb-0 small footer-muted">Fast checkout, secure login, premium UI experience.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?php echo isset($APP_PATH) ? $APP_PATH : ''; ?>/admin-login" class="btn btn-sm btn-outline-light">Admin</a>
            </div>
        </div>
    </div>
</footer>

<button id="scrollTopBtn" class="scroll-top-btn" type="button" aria-label="Scroll to top">Top</button>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo isset($APP_PATH) ? $APP_PATH : ''; ?>/assets/js/main.js"></script>
</body>

</html>