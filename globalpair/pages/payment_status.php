<?php
/**
 * GlobePair - Payment Status
 */

requireLogin();

$status_payment_id = intval($_GET['id'] ?? 0);
$status_type = sanitize($_GET['type'] ?? 'premium');

if ($status_type === 'message') {
    $status_payment = R::load('messagepayment', $status_payment_id);
} else {
    $status_payment = R::load('payment', $status_payment_id);
}

$page_title = 'Payment Status - GlobePair';

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <div class="spinner-border text-primary" role="status" id="paymentSpinner">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Processing Payment...</h4>
                    <p class="text-muted">Please check your phone and enter your M-Pesa PIN to complete the transaction.</p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-clock"></i> This page will update automatically. You can also check back later from your dashboard.
                    </div>
                    
                    <div class="mt-4">
                        <a href="?action=dashboard" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>