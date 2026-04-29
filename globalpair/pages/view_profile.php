<?php
/**
 * GlobePair - View Profile
 */

$user_id = intval($_GET['id'] ?? 0);
if (!$user_id) header('Location: ?action=discover');

$profile = R::load('user', $user_id);
if (!$profile || !$profile->id || $profile->is_admin) header('Location: ?action=discover');

$page_title = htmlspecialchars($profile->first_name . ' ' . $profile->last_name) . ' - GlobePair';

$is_favorite = false;
if ($current_user) {
    $favorite = R::findOne('favorite', 'user_id = ? AND favorite_user_id = ?', [$current_user->id, $user_id]);
    $is_favorite = (bool) $favorite;
}

$can_chat = $current_user && canSendMessage($current_user, $profile);

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <a href="?action=discover" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Back</a>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg overflow-hidden position-relative" style="height: 600px;">
                    <?php if ($profile->profile_image): ?>
                        <img src="<?php echo htmlspecialchars($profile->profile_image); ?>" class="w-100 h-100" style="object-fit: cover;">
                    <?php else: ?>
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient text-white">
                            <i class="bi bi-person-circle" style="font-size: 8rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($profile->user_role === 'premium'): ?>
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-warning"><i class="bi bi-star-fill"></i> Premium</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6 col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h1 class="display-5 fw-bold mb-2">
                            <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                        </h1>
                        
                        <div class="mb-4">
                            <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile->city); ?></p>
                            <p class="text-muted mb-1"><i class="bi bi-venus-mars"></i> <?php echo ucfirst($profile->gender); ?></p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-calendar"></i> 
                                <?php echo date_diff(date_create($profile->birth_date), date_create('today'))->y . ' years old'; ?>
                            </p>
                        </div>

                        <hr>

                        <h4 class="mb-3">About Me</h4>
                        <p class="text-muted fs-5 mb-4"><?php echo nl2br(htmlspecialchars($profile->bio)); ?></p>

                        <?php if ($current_user && $current_user->id !== $user_id): ?>
                            <div class="d-grid gap-2 d-md-flex">
                                <?php if ($can_chat): ?>
                                    <a href="?action=chat&user_id=<?php echo $user_id; ?>" class="btn btn-primary btn-lg">
                                        <i class="bi bi-chat-dots"></i> Send Message
                                    </a>
                                <?php else: ?>
                                    <a href="?action=payment_required&user_id=<?php echo $user_id; ?>" class="btn btn-warning btn-lg">
                                        <i class="bi bi-lock"></i> Pay to Message
                                    </a>
                                <?php endif; ?>
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="action" value="add_favorite">
                                    <input type="hidden" name="favorite_user_id" value="<?php echo $user_id; ?>">
                                    <button type="submit" class="btn <?php echo $is_favorite ? 'btn-danger' : 'btn-outline-danger'; ?> btn-lg w-100">
                                        <i class="bi <?php echo $is_favorite ? 'bi-heart-fill' : 'bi-heart'; ?>"></i> 
                                        <?php echo $is_favorite ? 'Liked' : 'Like'; ?>
                                    </button>
                                </form>
                            </div>
                        <?php elseif (!$current_user): ?>
                            <div class="alert alert-info">
                                <a href="?action=chat_redirect&user_id=<?php echo $user_id; ?>" class="btn btn-primary">
                                    <i class="bi bi-chat-dots"></i> Register to Chat
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>