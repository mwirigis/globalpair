<?php
/**
 * GlobePair - Payment Required
 */

$page_title = 'Payment Required - GlobePair';
requireLogin();

$payment_user_id = intval($_GET['user_id'] ?? ($_SESSION['message_redirect'] ?? 0));
unset($_SESSION['message_redirect']);
$payment_target_user = R::load('user', $payment_user_id);

if ($payment_target_user->id && canSendMessage($current_user, $payment_target_user)) {
    header('Location: ?action=chat&user_id=' . $payment_user_id);
    exit;
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-white">
                    <h3 class="mb-0 fw-bold">
                        <i class="bi bi-lock"></i> Payment Required to Message
                    </h3>
                </div>
                <div class="card-body p-5">
                    <?php if ($current_user->user_role === 'premium'): ?>
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i> 
                            You are a premium member, but the recipient is not. They need to be premium for free messaging, or you can pay a small fee.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-star"></i> 
                            <strong>Tip:</strong> Upgrade to <a href="?action=premium" class="fw-bold">Premium</a> for unlimited messaging with other premium members!
                        </div>
                    <?php endif; ?>

                    <?php if ($payment_target_user->id): ?>
                        <div class="text-center mb-4">
                            <p class="text-muted">You want to message:</p>
                            <h4><?php echo htmlspecialchars($payment_target_user->first_name . ' ' . $payment_target_user->last_name); ?></h4>
                            <p class="text-muted small"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($payment_target_user->city); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <!-- M-PESA Option -->
                        <div class="col-md-6">
                            <div class="payment-option h-100">
                                <div class="text-center mb-3">
                                    <i class="bi bi-phone" style="font-size: 3rem; color: #4CAF50;"></i>
                                    <h5 class="mt-2">M-Pesa</h5>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="display-5 fw-bold text-success">KES <?php echo number_format(MESSAGE_FEE_KES); ?></span>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="action" value="pay_message_mpesa">
                                    <input type="hidden" name="to_user_id" value="<?php echo $payment_user_id; ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">M-Pesa Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+</span>
                                            <input type="tel" class="form-control" name="phone" placeholder="254712345678" pattern="254[0-9]{9}" required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100 fw-bold">
                                        <i class="bi bi-check-circle"></i> Pay with M-Pesa
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- PayPal Option -->
                        <div class="col-md-6">
                            <div class="payment-option h-100">
                                <div class="text-center mb-3">
                                    <i class="bi bi-paypal" style="font-size: 3rem; color: #003087;"></i>
                                    <h5 class="mt-2">PayPal / Credit Card</h5>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="display-5 fw-bold" style="color: #003087;">$<?php echo number_format(MESSAGE_FEE_USD, 2); ?></span>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="action" value="pay_message_paypal">
                                    <input type="hidden" name="to_user_id" value="<?php echo $payment_user_id; ?>">
                                    
                                    <div class="alert alert-info small">
                                        <i class="bi bi-info-circle"></i> You'll be redirected to PayPal to complete payment securely. Accepts PayPal balance, credit/debit cards.
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="background-color: #003087;">
                                        <i class="bi bi-paypal"></i> Pay with PayPal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="?action=discover" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Browse
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>