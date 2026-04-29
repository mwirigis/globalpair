<?php
/**
 * GlobePair - Admin Panel
 */

$page_title = 'Admin Panel - GlobePair';
requireAdmin();

$admin_search = sanitize($_GET['search'] ?? '');
$admin_status = sanitize($_GET['status'] ?? '');
$admin_page = max(1, intval($_GET['page'] ?? 1));
$admin_per_page = 20;

$admin_conditions = ['is_admin = 0'];
$admin_params = [0];

if ($admin_search) {
    $admin_conditions[] = '(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)';
    $s = '%' . $admin_search . '%';
    $admin_params[] = $s; $admin_params[] = $s; $admin_params[] = $s;
}

if ($admin_status) {
    $admin_conditions[] = 'status = ?';
    $admin_params[] = $admin_status;
}

$admin_where = implode(' AND ', $admin_conditions);
$admin_users = R::find('user', $admin_where . ' ORDER BY created_at DESC LIMIT ? OFFSET ?',
    array_merge($admin_params, [$admin_per_page, ($admin_page - 1) * $admin_per_page]));
$admin_total = R::count('user', $admin_where, $admin_params);
$admin_total_pages = ceil($admin_total / $admin_per_page);

$stats = [
    'total_users' => R::count('user', 'is_admin = 0'),
    'active_users' => R::count('user', 'is_admin = 0 AND status = "active"'),
    'inactive_users' => R::count('user', 'is_admin = 0 AND status = "inactive"'),
    'premium_users' => R::count('user', 'is_admin = 0 AND user_role = "premium"'),
    'total_messages' => R::count('message'),
    'total_payments' => R::count('payment', 'status = "completed"') + R::count('messagepayment', 'status = "completed"'),
];

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-shield-lock text-warning"></i> Admin Panel</h1>
            <a href="?action=dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-5">
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Total Users</h6>
                    <h3 class="fw-bold text-primary"><?php echo $stats['total_users']; ?></h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Active</h6>
                    <h3 class="fw-bold text-success"><?php echo $stats['active_users']; ?></h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Inactive</h6>
                    <h3 class="fw-bold text-danger"><?php echo $stats['inactive_users']; ?></h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Premium</h6>
                    <h3 class="fw-bold text-warning"><?php echo $stats['premium_users']; ?></h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Messages</h6>
                    <h3 class="fw-bold text-info"><?php echo $stats['total_messages']; ?></h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted">Payments</h6>
                    <h3 class="fw-bold text-secondary"><?php echo $stats['total_payments']; ?></h3>
                </div>
            </div>
        </div>

        <!-- User Management -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="bi bi-people"></i> User Management</h4>
            </div>
            <div class="card-body p-4">
                <form method="GET" class="row g-3 mb-4">
                    <input type="hidden" name="action" value="admin">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($admin_search); ?>" placeholder="Search users...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="active" <?php echo $admin_status === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $admin_status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admin_users as $u): ?>
                                <tr>
                                    <td><?php echo $u->id; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($u->profile_image): ?>
                                                <img src="<?php echo htmlspecialchars($u->profile_image); ?>" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                            <?php else: ?>
                                                <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($u->first_name . ' ' . $u->last_name); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($u->email); ?></td>
                                    <td>
                                        <?php if ($u->user_role === 'premium'): ?>
                                            <span class="badge bg-warning"><i class="bi bi-star-fill"></i> Premium</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Regular</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $u->status === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                            <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> 
                                            <?php echo ucfirst($u->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($u->created_at)); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="user_id" value="<?php echo $u->id; ?>">
                                            <input type="hidden" name="new_status" value="<?php echo $u->status === 'active' ? 'inactive' : 'active'; ?>">
                                            <button type="submit" class="btn btn-sm <?php echo $u->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success'; ?>" 
                                                    onclick="return confirm('Are you sure you want to <?php echo $u->status === 'active' ? 'deactivate' : 'activate'; ?> this user?')">
                                                <i class="bi bi-<?php echo $u->status === 'active' ? 'pause-circle' : 'play-circle'; ?>"></i>
                                                <?php echo $u->status === 'active' ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($admin_total_pages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $admin_total_pages; $i++): ?>
                                <li class="page-item <?php echo $i === $admin_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?action=admin&page=<?php echo $i; ?>&search=<?php echo urlencode($admin_search); ?>&status=<?php echo urlencode($admin_status); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>