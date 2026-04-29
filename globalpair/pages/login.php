<?php
/**
 * GlobePair - Login Page
 */

$page_title = 'Login - GlobePair';

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Welcome Back</h2>
                        <p class="text-muted">Login to your GlobePair account</p>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold mb-3">
                            <i class="bi bi-lock"></i> Login
                        </button>

                        <p class="text-center text-muted">
                            Don't have an account? 
                            <a href="?action=register" class="text-decoration-none fw-bold">Register here</a>
                        </p>
                    </form>
                </div>
            </div>

            <div class="card mt-4 bg-info bg-opacity-10 border-info">
                <div class="card-body">
                    <small class="text-muted">
                        <strong>📝 Demo Credentials:</strong><br>
                        User: alice@globepair.com / alice123<br>
                        Admin: admin@globepair.com / admin123
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>