<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VELORA — Luxury Explore</title>
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="stylesheet" href="<?= asset('css/luxury.css'); ?>?v=<?= time(); ?>">
    <script>
        window.LOGIN_URL = "<?= url('login'); ?>";
    </script>
</head>
<body id="velora-body">

    <!-- Floating Background Elements -->
    <div class="v-blob v-blob-1"></div>
    <div class="v-blob v-blob-2"></div>
    <div id="particles-container"></div>

    <!-- Main Wrapper for Animation Transition -->
    <div id="app-wrapper">
        
        <!-- Top Navigation (72px) -->
        <nav class="v-navbar">
            <div class="v-nav-left">
                <a href="#" class="v-logo">
                    <span class="v-logo-icon"><i class="fas fa-gem"></i></span>
                    <span class="v-logo-text">VELORA</span>
                </a>
            </div>
            
            <div class="v-nav-center">
                <div class="v-search-bar" id="v-search-bar">
                    <i class="fas fa-search v-search-icon"></i>
                    <input type="text" id="v-search-input" placeholder="Search outfits, sneakers, aesthetics..." autocomplete="off">
                    
                    <!-- Search Dropdown Suggestion Panel -->
                    <div class="v-search-dropdown" id="v-search-dropdown">
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> Cream Outfit</div>
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> White Sneakers</div>
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> Linen Pants</div>
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> Old Money Fashion</div>
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> College Look</div>
                        <div class="v-dropdown-item"><i class="fas fa-search"></i> Krishna Aesthetic</div>
                    </div>
                </div>
            </div>
            
            <div class="v-nav-right">
                <button class="v-icon-btn"><i class="fas fa-camera"></i></button>
                <button class="v-icon-btn"><i class="fas fa-microphone"></i></button>
                <div class="v-profile transition-trigger">
                    <div class="v-avatar">P</div>
                    <i class="fas fa-chevron-down v-dropdown-arrow"></i>
                </div>
            </div>
        </nav>

        <!-- Category Tabs -->
        <div class="v-tabs-wrapper">
            <div class="v-tabs">
                <button class="v-tab active">All</button>
                <button class="v-tab">Men</button>
                <button class="v-tab">Women</button>
                <button class="v-tab">Sneakers</button>
                <button class="v-tab">Streetwear</button>
                <button class="v-tab">Old Money</button>
                <button class="v-tab">Linen Fits</button>
                <button class="v-tab">College Looks</button>
                <button class="v-tab">Accessories</button>
                <button class="v-tab">Krishna Art</button>
                <button class="v-tab">Couple Mood</button>
                <button class="v-tab">Home Decor</button>
                <button class="v-tab">Trending</button>
            </div>
        </div>

        <div class="v-content-layout">
            <!-- Glass Sidebar -->
            <aside class="v-sidebar">
                <a href="#" class="v-side-item active" title="Home"><i class="fas fa-home"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Explore"><i class="fas fa-compass"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Collections"><i class="fas fa-layer-group"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Saved"><i class="fas fa-bookmark"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Notifications"><i class="fas fa-bell"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Messages"><i class="fas fa-envelope"></i></a>
                <a href="#" class="v-side-item transition-trigger" title="Settings"><i class="fas fa-cog"></i></a>
            </aside>

            <!-- Main Content Area -->
            <main class="v-main">
                <!-- Masonry Grid -->
                <div class="v-grid" id="v-grid">
                    <!-- Cards injected by JS -->
                </div>
                
                <div id="loader" class="v-loader">
                    <div class="v-spinner"></div>
                </div>
            </main>
        </div>
    </div>

    <!-- Glow Cursor Trail -->
    <div id="v-cursor"></div>

    <script src="<?= asset('js/luxury.js'); ?>?v=<?= time(); ?>"></script>
</body>
</html>
