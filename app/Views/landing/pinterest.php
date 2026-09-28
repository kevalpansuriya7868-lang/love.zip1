<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspiration - Private Space</title>
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Pinterest Specific CSS -->
    <link rel="stylesheet" href="<?= asset('css/pinterest.css'); ?>?v=<?= time(); ?>">
</head>
<body>

    <!-- Pinterest Clone Top Navbar -->
    <nav class="pin-navbar">
        <a href="#" class="pin-logo">
            <i class="fab fa-pinterest" style="font-size: 28px;"></i>
        </a>
        
        <a href="#" class="pin-nav-btn active">Home</a>
        <a href="#" class="pin-nav-btn">Explore</a>
        <a href="#" class="pin-nav-btn">Create</a>
        
        <div class="pin-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search for ideas...">
        </div>
        
        <a href="#" class="pin-icon-btn"><i class="fas fa-bell"></i></a>
        <a href="#" class="pin-icon-btn"><i class="fas fa-comment-dots"></i></a>
        <a href="#" class="pin-icon-btn"><i class="fas fa-user-circle"></i></a>
        <a href="#" class="pin-icon-btn"><i class="fas fa-chevron-down" style="font-size: 16px;"></i></a>
        
        <!-- Routes to the actual application login -->
        <a href="<?= url('login'); ?>" class="pin-auth-btn pin-login">Log in</a>
        <a href="<?= url('login'); ?>" class="pin-auth-btn pin-signup">Sign up</a>
    </nav>

    <!-- Masonry Container -->
    <div class="pin-container">
        <div class="pin-grid" id="pin-grid">
            <!-- Pin items will be injected here via JavaScript -->
        </div>
        
        <!-- Infinite Scroll Loader -->
        <div id="loader">
            <i class="fas fa-spinner fa-spin"></i> Loading more inspiration...
        </div>
    </div>

    <script src="<?= asset('js/pinterest.js'); ?>?v=<?= time(); ?>"></script>
</body>
</html>
