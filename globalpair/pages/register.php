<?php
/**
 * GlobePair - Registration Page
 */

$page_title = 'Register - GlobePair';
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Create Account</h2>
                        <p class="text-muted">Join GlobePair today</p>
                    </div>

                    <?php if (!empty($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <div><i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); endif; ?>

                    <?php if (!empty($_SESSION['chat_redirect'])): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Register to start chatting with members!
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="register">
                        
                        <div class="text-center mb-4">
                            <div class="photo-upload-container" id="photoUploadContainer">
                                <div class="photo-placeholder" id="photoPlaceholder">
                                    <i class="bi bi-camera"></i>
                                    <span>Add Photo</span>
                                </div>
                                <img id="photoPreview" src="" alt="" style="display: none;">
                                <div class="photo-overlay">
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/jpeg,image/png,image/gif,image/webp">
                            </div>
                            <div class="mt-2">
                                <small class="file-size-hint">
                                    <i class="bi bi-info-circle"></i> JPG, PNG, GIF or WebP (Max 5MB)
                                </small>
                            </div>
                            <div id="fileName" class="mt-1" style="display: none;">
                                <small class="text-success"><i class="bi bi-check-circle"></i> <span id="fileNameText"></span></small>
                                <button type="button" class="btn btn-link text-danger p-0 ms-1" id="removePhoto" style="font-size: 0.75rem; text-decoration: none;">
                                    <i class="bi bi-x-circle"></i> Remove
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($form_data['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" name="birth_date" value="<?php echo htmlspecialchars($form_data['birth_date'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Select...</option>
                                    <option value="male" <?php echo ($form_data['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo ($form_data['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo ($form_data['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="3" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($form_data['bio'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold mb-3">
                            <i class="bi bi-person-plus"></i> Create Account
                        </button>

                        <p class="text-center text-muted">
                            Already have an account? 
                            <a href="?action=login" class="text-decoration-none fw-bold">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>