<?php
/**
 * GlobePair - My Payments
 */

$page_title = 'My Payments - GlobePair';
requireLogin();

$message_payments = R::find('messagepayment', 'user_id = ? ORDER BY created_at DESC', [$current_user->id]);
$premium_payments = R::find('payment', 'user_id = ? ORDER BY created_at DESC', [$current_user->id]);

include 'includes/header.php';
?>

<div class="container py-5">
    <h1 class="mb-4">My Payments</h1>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#msg-payments">Message Fees</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#prem-payments">Premium Payments</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="msg-payments">
            <?php if (empty($message_payments)): ?>
                <div class="alert alert-info">No message payments yet.</div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($message_payments as $p): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($p->created_at)); ?></td>
                                        <td><?php echo strtoupper($p->currency ?? 'KES') . ' ' . number_format($p->amount); ?></td>
                                        <td><?php echo ucfirst($p->payment_method); ?></td>
                                        <td>
                                            <span class="badge <?php echo $p->status === 'completed' ? 'bg-success' : ($p->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger'); ?>">
                                                <?php echo ucfirst($p->status); ?>
                                            </span>
                                        </td>
                                        <td><small><?php echo htmlspecialchars($p->mpesa_receipt ?? $p->paypal_order_id ?? $p->reference ?? '-'); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="prem-payments">
            <?php if (empty($premium_payments)): ?>
                <div class="alert alert-info">No premium payments yet.</div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($premium_payments as $p): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($p->created_at)); ?></td>
                                        <td>KES <?php echo number_format($p->amount); ?></td>
                                        <td>
                                            <span class="badge <?php echo $p->status === 'completed' ? 'bg-success' : ($p->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger'); ?>">
                                                <?php echo ucfirst($p->status); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($p->mpesa_receipt ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>