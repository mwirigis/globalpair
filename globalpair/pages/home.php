<?php
/**
 * GlobePair - Home Page
 * Landing page with member browsing
 */

$page_title = 'GlobePair - Find Your Perfect Match';

$home_search = sanitize($_GET['search'] ?? '');
$home_gender = sanitize($_GET['gender'] ?? '');
$home_city = sanitize($_GET['city'] ?? '');

$conditions = ['status = ?', 'is_admin = 0'];
$params = ['active', 0];

if ($home_gender) {
    $conditions[] = 'gender = ?';
    $params[] = $home_gender;
}
if ($home_city) {
    $conditions[] = 'city LIKE ?';
    $params[] = '%' . $home_city . '%';
}
if ($home_search) {
    $conditions[] = '(first_name LIKE ? OR last_name LIKE ? OR bio LIKE ?)';
    $search_param = '%' . $home_search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where = implode(' AND ', $conditions);
$home_users = R::find('user', $where . ' ORDER BY created_at DESC LIMIT 12', $params);

include 'includes/header.php';
?>

<!-- Hero -->
<section class="hero-section text-white">
    <div class="container text-center py-5">
        <h1 class="display-3 fw-bold mb-4">Find Your Perfect Match</h1>
        <p class="lead mb-5">Join thousands of singles looking for genuine connections</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <?php if (!$current_user): ?>
                <a href="?action=register" class="btn btn-light btn-lg px-5">Get Started</a>
                <a href="?action=login" class="btn btn-outline-light btn-lg px-5">Login</a>
            <?php else: ?>
                <a href="?action=discover" class="btn btn-light btn-lg px-5">
                    <i class="bi bi-search"></i> Discover People
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Why Choose GlobePair?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <i class="bi bi-shield-check" style="font-size: 3rem; color: var(--primary);"></i>
                    <h5 class="card-title mt-3">100% Secure</h5>
                    <p class="card-text text-muted">End-to-end encrypted messages keep your conversations safe</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <i class="bi bi-chat-dots" style="font-size: 3rem; color: var(--primary);"></i>
                    <h5 class="card-title mt-3">Easy Messaging</h5>
                    <p class="card-text text-muted">Connect with matches instantly and build relationships</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm text-center p-4">
                    <i class="bi bi-star-fill" style="font-size: 3rem; color: var(--primary);"></i>
                    <h5 class="card-title mt-3">Premium Features</h5>
                    <p class="card-text text-muted">Unlock advanced features with affordable payment options</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <h3 class="text-primary fw-bold">10,000+</h3>
                <p class="text-muted">Active Users</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-success fw-bold">2,500+</h3>
                <p class="text-muted">Success Stories</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-warning fw-bold">50,000+</h3>
                <p class="text-muted">Messages Daily</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-danger fw-bold">24/7</h3>
                <p class="text-muted">Support</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Find Love?</h2>
        <p class="lead mb-5">Join GlobePair today and start your journey</p>
        <?php if (!$current_user): ?>
            <a href="?action=register" class="btn btn-light btn-lg px-5">Sign Up Now</a>
        <?php else: ?>
            <a href="?action=discover" class="btn btn-light btn-lg px-5">Start Exploring</a>
        <?php endif; ?>
    </div>
</section>

<!-- MEMBERS LISTING -->
<section class="py-5 bg-light" id="members">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Browse Our Members</h2>
        <p class="text-center text-muted mb-5">Find someone who catches your eye</p>

        <!-- Search Form -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form method="GET" class="row g-3" id="homeSearchForm">
                    <input type="hidden" name="action" value="home">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($home_search); ?>" placeholder="Search by name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="">All</option>
                            <option value="male" <?php echo $home_gender === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $home_gender === 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($home_city); ?>" placeholder="Search by city...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($home_users)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">No members found. Try adjusting your search!</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($home_users as $profile): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0 profile-card overflow-hidden">
                            <div class="profile-image position-relative">
                                <?php if ($profile->profile_image): ?>
                                    <img src="<?php echo htmlspecialchars($profile->profile_image); ?>" alt="<?php echo htmlspecialchars($profile->first_name); ?>">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if ($profile->user_role === 'premium'): ?>
                                    <span class="badge bg-warning position-absolute top-2 end-2">
                                        <i class="bi bi-star-fill"></i> Premium
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile->city); ?>
                                </p>
                                <p class="card-text small text-muted" style="max-height: 60px; overflow: hidden;">
                                    <?php echo htmlspecialchars(substr($profile->bio, 0, 80)) . '...'; ?>
                                </p>
                            </div>
                            <div class="card-footer bg-light border-top p-2">
                                <div class="d-grid gap-2 d-md-flex">
                                    <a href="?action=view_profile&id=<?php echo $profile->id; ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="?action=chat_redirect&user_id=<?php echo $profile->id; ?>" class="btn btn-sm btn-primary flex-fill">
                                        <i class="bi bi-chat-dots"></i> Chat
                                    </a>
                                    <?php if ($current_user): ?>
                                        <form method="POST" style="display: inline; flex: 0;">
                                            <input type="hidden" name="action" value="add_favorite">
                                            <input type="hidden" name="favorite_user_id" value="<?php echo $profile->id; ?>">
                                            <input type="hidden" name="redirect" value="home">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>