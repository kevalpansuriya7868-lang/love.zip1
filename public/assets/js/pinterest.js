document.addEventListener("DOMContentLoaded", function() {
    const grid = document.getElementById("pin-grid");
    const loader = document.getElementById("loader");

    // Array of possible heights to simulate masonry grid irregularity
    const possibleHeights = [250, 300, 350, 400, 450, 500, 600];

    function getRandomHeight() {
        return possibleHeights[Math.floor(Math.random() * possibleHeights.length)];
    }

    function createPinItem() {
        const item = document.createElement('div');
        item.className = 'pin-item';
        
        const h = getRandomHeight();
        // Using picsum photos for beautiful placeholder imagery
        const imgUrl = `https://picsum.photos/400/${h}?random=${Math.random()}`;
        
        item.innerHTML = `
            <img src="${imgUrl}" loading="lazy" alt="Pin Image">
            <div class="pin-item-overlay">
                <button class="pin-save-btn">Save</button>
                <div class="pin-bottom-actions">
                    <a href="#" class="pin-link-btn"><i class="fas fa-external-link-alt"></i> love.example.com</a>
                    <div class="pin-icon-actions">
                        <div class="pin-small-icon"><i class="fas fa-share-alt"></i></div>
                        <div class="pin-small-icon"><i class="fas fa-ellipsis-h"></i></div>
                    </div>
                </div>
            </div>
        `;
        return item;
    }

    function loadItems(count) {
        for (let i = 0; i < count; i++) {
            grid.appendChild(createPinItem());
        }
    }

    // Initial load: 30 images
    loadItems(30);

    // Intersection Observer for infinite scrolling
    // "never stop hwen you dont stop it"
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                // When loader enters viewport, load 15 more items
                // Add a small delay for realistic loading feel
                setTimeout(() => {
                    loadItems(15);
                }, 300);
            }
        }, {
            rootMargin: "300px" // Start loading before it actually hits the screen
        });

        observer.observe(loader);
    } else {
        // Fallback for older browsers
        window.addEventListener('scroll', function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loadItems(10);
            }
        });
    }
});
