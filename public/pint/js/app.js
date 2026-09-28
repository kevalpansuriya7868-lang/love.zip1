document.addEventListener('DOMContentLoaded', () => {
    // 1. Smooth Scrolling with Lenis
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // https://www.desmos.com/calculator/brs54l4xou
        direction: 'vertical',
        gestureDirection: 'vertical',
        smooth: true,
        mouseMultiplier: 1,
        smoothTouch: false,
        touchMultiplier: 2,
        infinite: false,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // 2. Custom Cursor & Glow Trail
    const cursorDot = document.querySelector('.cursor-dot');
    const cursorGlow = document.querySelector('.cursor-glow');
    
    let mouseX = 0;
    let mouseY = 0;
    let dotX = 0;
    let dotY = 0;
    let glowX = 0;
    let glowY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateCursor() {
        // Smooth follow for dot
        dotX += (mouseX - dotX) * 0.2;
        dotY += (mouseY - dotY) * 0.2;
        
        // Slower smooth follow for glow
        glowX += (mouseX - glowX) * 0.1;
        glowY += (mouseY - glowY) * 0.1;

        cursorDot.style.left = `${dotX}px`;
        cursorDot.style.top = `${dotY}px`;
        
        cursorGlow.style.left = `${glowX}px`;
        cursorGlow.style.top = `${glowY}px`;

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover effect for cursor
    const interactiveElements = document.querySelectorAll('button, a, input, .pin-card');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorDot.style.transform = 'translate(-50%, -50%) scale(1.5)';
            cursorGlow.style.transform = 'translate(-50%, -50%) scale(1.5)';
            cursorDot.style.backgroundColor = 'var(--accent)';
        });
        el.addEventListener('mouseleave', () => {
            cursorDot.style.transform = 'translate(-50%, -50%) scale(1)';
            cursorGlow.style.transform = 'translate(-50%, -50%) scale(1)';
            cursorDot.style.backgroundColor = 'var(--text-primary)';
        });
    });

    // 3. Magnetic Buttons
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
            // Change color on magnetic hover
            if(btn.querySelector('i')) {
                btn.querySelector('i').style.color = 'var(--text-primary)';
            }
        });

        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0px, 0px)';
            if(btn.querySelector('i') && !btn.classList.contains('active')) {
                btn.querySelector('i').style.color = '';
            }
        });
    });

    // 4. Populate Masonry Grid
    const masonryGrid = document.getElementById('masonryGrid');
    
    // Gemini Generated Images
    const geminiImages = [
        'pint/assets/gemini_images/aesthetic_accessories_1_1789214079918.jpg',
        'pint/assets/gemini_images/fashion_streetwear_1_1789214000166.jpg',
        'pint/assets/gemini_images/luxury_bag_1_1789214197461.jpg',
        'pint/assets/gemini_images/luxury_car_aesthetic_1_1789214112364.jpg',
        'pint/assets/gemini_images/luxury_watch_1_1789214014667.jpg',
        'pint/assets/gemini_images/mens_college_fit_1_1789214067621.jpg',
        'pint/assets/gemini_images/mens_fashion_suit_1_1789214171906.jpg',
        'pint/assets/gemini_images/minimalist_fashion_1_1789214128677.jpg',
        'pint/assets/gemini_images/old_money_womens_1_1789214038727.jpg',
        'pint/assets/gemini_images/sneakers_hype_1_1789214052613.jpg',
        'pint/assets/gemini_images/street_sneaker_shot_1_1789214145146.jpg',
        'pint/assets/gemini_images/streetwear_hoodie_2_1789214184090.jpg',
        'pint/assets/gemini_images/womens_streetwear_1_1789214098095.jpg'
    ];

    let imageIndex = 0;
    let isLoading = false;

    function createPinCard(src) {
        const card = document.createElement('div');
        card.className = 'pin-card transition-trigger';
        // Only animate the first few cards to avoid weird popping on scroll
        const delay = imageIndex < 20 ? (imageIndex % 20) * 0.05 : 0;
        // Generate a random height to maintain the masonry layout effect
        // Scale down the height for mobile screens to keep good proportions
        const isMobile = window.innerWidth <= 768;
        const minHeight = isMobile ? 150 : 250;
        const heightVariance = isMobile ? 100 : 250;
        const randomHeight = Math.floor(Math.random() * heightVariance) + minHeight;
        
        card.innerHTML = `
            <img src="${src}" alt="Pin Image" class="pin-image" loading="lazy" style="height: ${randomHeight}px; object-fit: cover;">
            <div class="pin-overlay">
                <div class="pin-top-bar">
                    <button class="save-btn">Save</button>
                </div>
                <div class="pin-bottom-bar">
                    <button class="action-icon"><i class="ph ph-heart"></i></button>
                    <button class="action-icon"><i class="ph ph-share-network"></i></button>
                    <button class="action-icon"><i class="ph ph-dots-three"></i></button>
                </div>
            </div>
        `;
        masonryGrid.appendChild(card);
        
        // Re-attach interactive element listeners for new cards
        card.addEventListener('mouseenter', () => {
            const cursorDot = document.querySelector('.cursor-dot');
            const cursorGlow = document.querySelector('.cursor-glow');
            if(cursorDot && cursorGlow) {
                cursorDot.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursorGlow.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursorDot.style.backgroundColor = 'var(--accent)';
            }
        });
        card.addEventListener('mouseleave', () => {
            const cursorDot = document.querySelector('.cursor-dot');
            const cursorGlow = document.querySelector('.cursor-glow');
            if(cursorDot && cursorGlow) {
                cursorDot.style.transform = 'translate(-50%, -50%) scale(1)';
                cursorGlow.style.transform = 'translate(-50%, -50%) scale(1)';
                cursorDot.style.backgroundColor = 'var(--text-primary)';
            }
        });
        
        imageIndex++;
    }

    function loadMoreImages(count) {
        if(isLoading) return;
        isLoading = true;
        
        for (let i = 0; i < count; i++) {
            let src = '';
            // Load gemini images first, then random picsum images
            if (imageIndex < geminiImages.length) {
                src = geminiImages[imageIndex];
            } else {
                const randomSeed = Math.floor(Math.random() * 100000);
                src = `https://picsum.photos/seed/${randomSeed}/400/600`;
            }
            createPinCard(src);
        }
        
        isLoading = false;
    }

    // Initial load (13 gemini + 27 random = 40)
    loadMoreImages(40);

    // Infinite Scroll Implementation
    window.addEventListener('scroll', () => {
        // If user scrolls near the bottom of the page (within 1000px)
        if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 1000) {
            // Load 20 more images automatically
            loadMoreImages(20);
        }
    });

    // 5. Cinematic Transition to Login (Removed as per request)
    /*
    const appWrapper = document.getElementById('appWrapper');
    const loginOverlay = document.getElementById('loginOverlay');
    const transitionTriggers = document.querySelectorAll('.transition-trigger, .search-bar input, .nav-btn[title="Explore"]');

    function triggerLoginTransition(e) {
        if(e) e.preventDefault();
        lenis.stop();
        appWrapper.classList.add('transitioning');
        setTimeout(() => {
            loginOverlay.classList.add('active');
        }, 100);
    }

    transitionTriggers.forEach(trigger => {
        trigger.addEventListener('click', triggerLoginTransition);
    });
    */
    
    // Category chips interaction
    const chips = document.querySelectorAll('.chip');
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
        });
    });

    // Sidebar navigation interaction
    const navBtns = document.querySelectorAll('.sidebar-nav .nav-btn');
    navBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            navBtns.forEach(b => {
                b.classList.remove('active');
                if(b.querySelector('i')) b.querySelector('i').style.color = '';
            });
            btn.classList.add('active');
            if(btn.querySelector('i')) btn.querySelector('i').style.color = 'var(--text-primary)';
        });
    });

    // 6. Pinterest Search Redirect
    const searchInput = document.querySelector('.search-input');
    const searchIcon = document.querySelector('.search-icon');

    function performSearch() {
        const query = searchInput.value.trim();
        if (query) {
            const encodedQuery = encodeURIComponent(query);
            const pinterestUrl = `https://www.pinterest.com/search/pins/?q=${encodedQuery}`;
            window.open(pinterestUrl, '_blank');
        }
    }

    // Search on Enter key press
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Search on icon click
    searchIcon.style.cursor = 'pointer'; // Ensure it looks clickable
    searchIcon.addEventListener('click', () => {
        performSearch();
    });
});
