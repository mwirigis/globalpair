<?php
/**
 * GlobePair - Edit Profile
 */

$page_title = 'Edit Profile - GlobePair';
requireLogin();

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Edit Your Profile</h3>
                </div>
                <div class="card-body p-5">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($current_user->first_name); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($current_user->last_name); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($current_user->city); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" name="birth_date" value="<?php echo $current_user->birth_date; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-select" name="gender" required>
                                    <option value="male" <?php echo $current_user->gender === 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo $current_user->gender === 'female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo $current_user->gender === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="5" placeholder="Tell others about yourself..."><?php echo htmlspecialchars($current_user->bio); ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold"><i class="bi bi-check-circle"></i> Save Changes</button>
                            <a href="?action=dashboard" class="btn btn-secondary">Back to Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>