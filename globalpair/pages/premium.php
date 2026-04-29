<?php
/**
 * GlobePair - Premium Upgrade
 */

$page_title = 'Premium - GlobePair';
requireLogin();

if ($current_user->user_role === 'premium') {
    header('Location: ?action=dashboard');
    exit;
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-gradient text-white">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-star-fill"></i> Premium Benefits</h3>
                </div>
                <div class="card-body p-5">
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>Free Messaging with Premium Members</h6>
                                <small class="text-muted">No per-message fees when chatting with other premium users</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>See Who Liked You</h6>
                                <small class="text-muted">Know who's interested in you</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>Priority Profile Visibility</h6>
                                <small class="text-muted">Appear higher in search results</small>
                            </div>
                        </li>
                        <li class="d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6>24/7 Priority Support</h6>
                                <small class="text-muted">Get help when you need it</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-credit-card"></i> Upgrade Now</h3>
                </div>
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="fw-bold">Monthly Premium</span>
                            <span class="fw-bold">KES <?php echo number_format(PREMIUM_PRICE); ?></span>
                        </div>
                        <div class="d-flex justify-content-between pt-2">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-success" style="font-size: 1.3rem;">KES <?php echo number_format(PREMIUM_PRICE); ?></span>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="upgrade_premium">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">M-Pesa Phone Number</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">+</span>
                                <input type="tel" class="form-control border-start-0" name="phone" placeholder="254712345678" pattern="254[0-9]{9}" required>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Enter your Kenyan phone number (format: 254712345678)
                            </small>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-3">
                            <i class="bi bi-phone"></i> Pay with M-Pesa
                        </button>

                        <div class="alert alert-info">
                            <i class="bi bi-shield-check"></i>
                            <small>Your payment is secure and processed by Safaricom M-Pesa.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>