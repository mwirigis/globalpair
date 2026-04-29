<?php
/**
 * GlobePair - Discover People
 */

$page_title = 'Discover - GlobePair';

$search = sanitize($_GET['search'] ?? '');
$gender = sanitize($_GET['gender'] ?? '');
$city = sanitize($_GET['city'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 12;

$conditions = [];
$params = [];

$conditions[] = 'status = ?';
$params[] = 'active';
$conditions[] = 'is_admin = ?';
$params[] = 0;

if ($current_user) {
    $conditions[] = 'id != ?';
    $params[] = $current_user->id;
}

if ($gender) { 
    $conditions[] = 'gender = ?'; 
    $params[] = $gender; 
}
if ($city) { 
    $conditions[] = 'city LIKE ?'; 
    $params[] = '%' . $city . '%'; 
}
if ($search) {
    $conditions[] = '(first_name LIKE ? OR last_name LIKE ? OR bio LIKE ?)';
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where = ' ' . implode(' AND ', $conditions);

$limit_params = $params;
$limit_params[] = $per_page;
$limit_params[] = ($page - 1) * $per_page;

$users = R::find('user', $where . ' ORDER BY created_at DESC LIMIT ? OFFSET ?', $limit_params);
$total = R::count('user', $where, $params);
$total_pages = ceil($total / $per_page);

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <h1 class="mb-4">Discover People</h1>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="action" value="discover">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="">All</option>
                            <option value="male" <?php echo $gender === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $gender === 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="Search by city...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($users)): ?>
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">No profiles found. Try adjusting your filters!</p>
            </div>
        <?php else: ?>
            <div class="row g-4 mb-5">
                <?php foreach ($users as $profile): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0 profile-card overflow-hidden">
                            <div class="profile-image position-relative">
                                <?php if ($profile->profile_image): ?>
                                    <img src="<?php echo htmlspecialchars($profile->profile_image); ?>" alt="Profile">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if ($profile->user_role === 'premium'): ?>
                                    <span class="badge bg-warning position-absolute top-2 end-2"><i class="bi bi-star-fill"></i></span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h5>
                                <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile->city); ?></p>
                                <p class="card-text small text-muted" style="max-height: 60px; overflow: hidden;">
                                    <?php echo htmlspecialchars(substr($profile->bio, 0, 80)) . '...'; ?>
                                </p>
                            </div>
                            <div class="card-footer bg-light border-top p-2">
                                <div class="d-grid gap-2 d-md-flex">
                                    <a href="?action=view_profile&id=<?php echo $profile->id; ?>" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> View</a>
                                    <?php if ($current_user): ?>
                                        <form method="POST" style="display: inline; flex: 1;">
                                            <input type="hidden" name="action" value="add_favorite">
                                            <input type="hidden" name="favorite_user_id" value="<?php echo $profile->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-heart"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mb-5">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?action=discover&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&gender=<?php echo urlencode($gender); ?>&city=<?php echo urlencode($city); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>