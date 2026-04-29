<?php
/**
 * GlobePair - Favorites
 */

$page_title = 'Favorites - GlobePair';
requireLogin();

$my_favorites = R::find('favorite', 'user_id = ? ORDER BY created_at DESC', [$current_user->id]);
$received_favorites = R::find('favorite', 'favorite_user_id = ? ORDER BY created_at DESC', [$current_user->id]);

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <h1 class="mb-4">My Likes & Admirers</h1>

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#my-likes" type="button" role="tab">
                    <i class="bi bi-heart"></i> My Favorites (<?php echo count($my_favorites); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#admirers" type="button" role="tab">
                    <i class="bi bi-heart-fill text-danger"></i> Admirers (<?php echo count($received_favorites); ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="my-likes" role="tabpanel">
                <?php if (empty($my_favorites)): ?>
                    <div class="alert alert-info text-center py-5">
                        <i class="bi bi-heart" style="font-size: 3rem;"></i>
                        <p class="mt-3">You haven't liked anyone yet. <a href="?action=discover">Explore profiles</a></p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($my_favorites as $fav): 
                            $profile = R::load('user', $fav->favorite_user_id);
                            if (!$profile->is_admin):
                        ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 profile-card overflow-hidden">
                                    <div class="profile-image">
                                        <?php if ($profile->profile_image): ?>
                                            <img src="<?php echo htmlspecialchars($profile->profile_image); ?>" alt="Profile">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white bg-gradient">
                                                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h5>
                                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile->city); ?></p>
                                    </div>
                                    <div class="card-footer bg-light border-top p-2">
                                        <a href="?action=view_profile&id=<?php echo $profile->id; ?>" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-eye"></i> View</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="admirers" role="tabpanel">
                <?php if (empty($received_favorites)): ?>
                    <div class="alert alert-info text-center py-5">
                        <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3">No one has liked your profile yet.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($received_favorites as $fav): 
                            $profile = R::load('user', $fav->user_id);
                            if (!$profile->is_admin):
                        ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 profile-card overflow-hidden">
                                    <div class="profile-image position-relative">
                                        <?php if ($profile->profile_image): ?>
                                            <img src="<?php echo htmlspecialchars($profile->profile_image); ?>" alt="Profile">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white bg-gradient">
                                                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span class="badge bg-danger position-absolute top-2 end-2"><i class="bi bi-heart-fill"></i></span>
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h5>
                                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($profile->city); ?></p>
                                    </div>
                                    <div class="card-footer bg-light border-top p-2">
                                        <div class="d-grid gap-2">
                                            <a href="?action=chat_redirect&user_id=<?php echo $profile->id; ?>" class="btn btn-sm btn-primary"><i class="bi bi-chat-dots"></i> Message</a>
                                            <a href="?action=view_profile&id=<?php echo $profile->id; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>