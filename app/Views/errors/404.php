<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css'); ?>">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light text-center">

<div class="container">
    <div class="card-romantic p-5 max-w-500 mx-auto">
        <div class="fs-1 text-danger mb-2"><i class="fas fa-compass"></i></div>
        <h2 class="brand-font fw-bold text-dark mb-2">Page Not Found (404)</h2>
        <p class="text-muted small mb-4">The page or moment you are looking for doesn't exist or has moved.</p>
        <a href="<?= url('dashboard'); ?>" class="btn btn-romantic px-4">Return Home</a>
    </div>
</div>

</body>
</html>
