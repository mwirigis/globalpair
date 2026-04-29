<?php
/**
 * GlobePair - Dashboard
 */

$page_title = 'Dashboard - GlobePair';
requireLogin();

$message_count = R::count('message', 'to_user_id = ? AND is_read = 0', [$current_user->id]);
$favorite_count = R::count('favorite', 'user_id = ?', [$current_user->id]);
$received_favorites = R::count('favorite', 'favorite_user_id = ?', [$current_user->id]);
$pending_payments = R::find('messagepayment', 'user_id = ? AND status = ?', [$current_user->id, 'pending']);

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <h1 class="mb-4">Welcome back, <?php echo htmlspecialchars($current_user->first_name); ?>! 👋</h1>

        <?php if ($pending_payments && count($pending_payments) > 0): ?>
            <div class="alert alert-warning">
                <i class="bi bi-clock"></i> You have <?php echo count($pending_payments); ?> pending payment(s). 
                <a href="?action=my_payments" class="fw-bold">View Payments</a>
            </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title mb-0">Unread Messages</h6>
                        <h2 class="fw-bold mt-2"><?php echo $message_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title mb-0">Your Favorites</h6>
                        <h2 class="fw-bold mt-2"><?php echo $favorite_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title mb-0">Who Liked You</h6>
                        <h2 class="fw-bold mt-2"><?php echo $received_favorites; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm <?php echo $current_user->user_role === 'premium' ? 'bg-info' : 'bg-secondary'; ?> text-white">
                    <div class="card-body">
                        <h6 class="card-title mb-0">Status</h6>
                        <h2 class="fw-bold mt-2"><?php echo ucfirst($current_user->user_role); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-search" style="font-size: 2.5rem; color: var(--primary);"></i>
                        <h5 class="card-title mt-2">Discover People</h5>
                        <a href="?action=discover" class="btn btn-primary btn-sm">Start Exploring</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-chat-dots" style="font-size: 2.5rem; color: var(--primary);"></i>
                        <h5 class="card-title mt-2">Your Messages</h5>
                        <a href="?action=chat" class="btn btn-primary btn-sm">Go to Messages <?php if ($message_count): ?><span class="badge bg-danger ms-1"><?php echo $message_count; ?></span><?php endif; ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-receipt" style="font-size: 2.5rem; color: var(--primary);"></i>
                        <h5 class="card-title mt-2">My Payments</h5>
                        <a href="?action=my_payments" class="btn btn-primary btn-sm">View History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>