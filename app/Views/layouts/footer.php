</main>

<footer class="text-center py-4 text-muted small mt-5">
    <div class="container">
        <p class="mb-1">&copy; <?= date('Y'); ?> <?= e(config('app.name', 'Couples Private Website')); ?>. A private digital home for two.</p>
    </div>
</footer>

<!-- Lightbox Modal for Photo Viewer -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="lightboxImage" src="" class="img-fluid rounded-3 mb-2" style="max-height: 80vh; object-fit: contain;">
                <p id="lightboxCaption" class="text-light small mb-0 fw-medium"></p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Application Scripts -->
<script src="<?= asset('js/app.js'); ?>"></script>
<script src="<?= asset('js/photos.js'); ?>"></script>
<script src="<?= asset('js/bg-animation.js'); ?>"></script>

<!-- Custom Cursor & 3D Tilt Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Custom Cursor
        const cursorDot = document.querySelector('.cursor-dot');
        const cursorOutline = document.querySelector('.cursor-outline');

        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;

            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;
            
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 500, fill: "forwards" });
        });

        // Add hover effects for interactive elements to expand cursor
        const interactables = document.querySelectorAll('a, button, .gallery-card');
        interactables.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.classList.add('cursor-hover');
                cursorDot.classList.add('cursor-hover');
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.classList.remove('cursor-hover');
                cursorDot.classList.remove('cursor-hover');
            });
        });

        // 3D Tilt Effect
        const cards = document.querySelectorAll('.card-romantic');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = ((y - centerY) / centerY) * -10; // Max 10 deg
                const rotateY = ((x - centerX) / centerX) * 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            });
        });
    });
</script>
</body>
</html>
