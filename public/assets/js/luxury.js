document.addEventListener("DOMContentLoaded", function() {
    const grid = document.getElementById("v-grid");
    const loader = document.getElementById("loader");
    const cursor = document.getElementById("v-cursor");
    const searchBar = document.getElementById("v-search-bar");
    const searchInput = document.getElementById("v-search-input");

    // Glow Cursor Trail & Parallax base
    window.addEventListener('mousemove', (e) => {
        if (cursor) {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        }
        
        // Subtle Parallax on blobs
        const blobs = document.querySelectorAll('.v-blob');
        const x = (e.clientX / window.innerWidth - 0.5) * 40;
        const y = (e.clientY / window.innerHeight - 0.5) * 40;
        blobs.forEach(blob => {
            blob.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Particle Generation
    const particlesContainer = document.getElementById('particles-container');
    if (particlesContainer) {
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.width = Math.random() * 6 + 2 + 'px';
            p.style.height = p.style.width;
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = Math.random() * 100 + 'vh';
            p.style.opacity = Math.random() * 0.5 + 0.1;
            particlesContainer.appendChild(p);
        }
    }

    // Search Interaction
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchBar.classList.add('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!searchBar.contains(e.target)) {
                searchBar.classList.remove('active');
            }
        });
    }

    // Category Tabs Logic
    const tabs = document.querySelectorAll('.v-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            // Re-shuffle grid to simulate loading new category
            grid.innerHTML = '';
            loadItems(25);
        });
    });

    // Inertia Smooth Scrolling CSS Simulation
    document.documentElement.style.scrollBehavior = 'smooth';

    // Masonry Grid Generation
    const heights = [350, 400, 450, 500, 600, 700, 800];
    let delayCounter = 0;

    function createCard() {
        const card = document.createElement('div');
        card.className = 'v-card transition-trigger';
        
        const h = heights[Math.floor(Math.random() * heights.length)];
        const seed = Math.floor(Math.random() * 100000);
        const imgUrl = `https://picsum.photos/seed/${seed}/500/${h}`;
        
        card.style.animationDelay = `${(delayCounter % 20) * 0.08}s`;
        delayCounter++;
        
        card.innerHTML = `
            <img src="${imgUrl}" loading="lazy" alt="VELORA Aesthetic">
            <div class="v-card-overlay">
                <button class="v-save-btn">Save</button>
                <div class="v-card-actions">
                    <div class="v-action-icon"><i class="fas fa-heart"></i></div>
                    <div class="v-action-icon"><i class="fas fa-share"></i></div>
                    <div class="v-action-icon"><i class="fas fa-ellipsis-h"></i></div>
                </div>
            </div>
        `;
        return card;
    }

    function loadItems(count) {
        delayCounter = 0; 
        for (let i = 0; i < count; i++) {
            grid.appendChild(createCard());
        }
        attachTransitionListeners();
    }

    loadItems(30);

    // Infinite scroll
    if ('IntersectionObserver' in window && loader) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                setTimeout(() => {
                    loadItems(12);
                }, 600);
            }
        }, { rootMargin: "600px" });
        observer.observe(loader);
    }

    // --- Cinematic SPA Transition to Login ---
    let isTransitioning = false;

    function attachTransitionListeners() {
        const triggers = document.querySelectorAll('.transition-trigger');
        triggers.forEach(el => {
            if (el.dataset.transitionAttached) return;
            el.dataset.transitionAttached = true;
            
            el.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); 
                if (isTransitioning) return;
                triggerLoginTransition();
            });
        });
    }

    function triggerLoginTransition() {
        isTransitioning = true;
        const wrapper = document.getElementById('app-wrapper');
        
        // 1. Fetch Login HTML in background
        fetch(window.LOGIN_URL)
            .then(response => response.text())
            .then(html => {
                // 2. Play 900ms Cinematic Animation (Zoom, Blur, Fade, Glass dissolve)
                wrapper.style.transform = 'scale(0.85) translateY(-20px)';
                wrapper.style.filter = 'blur(30px)';
                wrapper.style.opacity = '0';
                document.body.style.background = '#111'; // Prep for dark theme
                
                // Hide cursor trail for transition
                if (cursor) cursor.style.opacity = '0';
                
                // 3. Inject HTML seamlessly
                setTimeout(() => {
                    document.open();
                    document.write(html);
                    document.close();
                    
                    window.history.pushState({}, "", window.LOGIN_URL);
                }, 900);
            })
            .catch(err => {
                console.error("Transition failed", err);
                window.location.href = window.LOGIN_URL;
            });
    }
    
    // Also trigger on suggestion click
    document.querySelectorAll('.v-dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            if (isTransitioning) return;
            triggerLoginTransition();
        });
    });
});
