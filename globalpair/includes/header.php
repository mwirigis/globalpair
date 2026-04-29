<?php
/**
 * GlobePair - Header Template
 * Navigation and top of page
 */
$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }

        body {
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        main { flex: 1; }

        .navbar { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; letter-spacing: 1px; }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .profile-card .profile-image {
            height: 300px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            overflow: hidden;
        }

        .profile-card .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-card:hover .profile-image img { transform: scale(1.05); }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .chat-container {
            height: 600px;
            display: flex;
            flex-direction: column;
        }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 20px;
        }

        .message { margin-bottom: 15px; display: flex; }
        .message.sent { justify-content: flex-end; }

        .message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 8px;
            word-wrap: break-word;
        }

        .message.sent .message-content { background: #007bff; color: white; }
        .message.received .message-content { background: white; border: 1px solid #ddd; }

        footer {
            margin-top: auto;
            background-color: #212529;
            color: white;
            padding: 2rem 0;
            border-top: 1px solid #dee2e6;
        }

        .bg-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .hover-effect {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hover-effect:hover {
            background-color: #f5f5f5 !important;
            transform: translateX(5px);
        }

        .photo-upload-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload-container:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .photo-upload-container:hover .photo-overlay { opacity: 1; }
        .photo-upload-container img { width: 100%; height: 100%; object-fit: cover; }

        .photo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: white;
        }

        .photo-placeholder i { font-size: 2.5rem; margin-bottom: 5px; }
        .photo-placeholder span { font-size: 0.7rem; text-align: center; }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .photo-overlay i { color: white; font-size: 2rem; }

        .photo-upload-container input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-size-hint { font-size: 0.75rem; color: #6c757d; }

        .payment-option {
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover, .payment-option.active {
            border-color: var(--primary);
            background-color: rgba(102, 126, 234, 0.05);
        }

        .status-active { color: #198754; }
        .status-inactive { color: #dc3545; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card { animation: fadeIn 0.5s ease-out; }
        .alert { animation: fadeIn 0.3s ease-out; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?action=home">
                <i class="bi bi-heart-fill text-danger"></i> GlobePair
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=discover">Discover</a>
                    </li>
                    
                    <?php if ($current_user): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?action=dashboard">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?action=chat">
                                <i class="bi bi-chat-dots"></i> Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?action=favorites">
                                <i class="bi bi-heart"></i> Favorites
                            </a>
                        </li>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="?action=admin">
                                    <i class="bi bi-shield-lock"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $current_user->first_name; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="?action=edit_profile">Edit Profile</a></li>
                                <?php if ($current_user->user_role === 'regular'): ?>
                                    <li><a class="dropdown-item" href="?action=premium">
                                        <i class="bi bi-star-fill text-warning"></i> Go Premium
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?action=logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?action=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm text-white ms-2" href="?action=register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['success']); endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['error']); endif; ?>