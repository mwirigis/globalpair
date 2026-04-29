<?php
/**
 * GlobePair - Chat/Messaging
 */

$page_title = 'Messages - GlobePair';
requireLogin();

$other_user_id = intval($_GET['user_id'] ?? 0);

if ($other_user_id) {
    $other_user_check = R::load('user', $other_user_id);
    if ($other_user_check->id && !canSendMessage($current_user, $other_user_check)) {
        header('Location: ?action=payment_required&user_id=' . $other_user_id);
        exit;
    }
}

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="container">
        <h1 class="mb-4">Messages</h1>

        <div class="row g-4" style="min-height: 600px;">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Conversations</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                        <?php 
                        $users_set = R::find('message', 'from_user_id = ? OR to_user_id = ? GROUP BY CASE WHEN from_user_id = ? THEN to_user_id ELSE from_user_id END ORDER BY created_at DESC',
                            [$current_user->id, $current_user->id, $current_user->id]);
                        
                        if (empty($users_set)): 
                        ?>
                            <div class="p-3 text-center text-muted">
                                <p>No conversations yet</p>
                                <a href="?action=discover" class="btn btn-sm btn-primary">Start Messaging</a>
                            </div>
                        <?php else: 
                            foreach ($users_set as $msg): 
                                $other_id = $msg->from_user_id === $current_user->id ? $msg->to_user_id : $msg->from_user_id;
                                $other = R::load('user', $other_id);
                                if ($other->id && !$other->is_admin):
                        ?>
                            <a href="?action=chat&user_id=<?php echo $other_id; ?>" class="list-group-item list-group-item-action text-decoration-none border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($other->first_name . ' ' . $other->last_name); ?></h6>
                                        <p class="text-muted small mb-0" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo htmlspecialchars(substr($msg->content, 0, 40)); ?>...
                                        </p>
                                    </div>
                                </div>
                            </a>
                        <?php 
                                endif;
                            endforeach; 
                        endif; 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <?php if ($other_user_id): ?>
                    <?php 
                    $other_user = R::load('user', $other_user_id);
                    $messages = R::find('message', '(from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?) ORDER BY created_at ASC',
                        [$current_user->id, $other_user_id, $other_user_id, $current_user->id]);
                    
                    $unread = R::find('message', 'from_user_id = ? AND to_user_id = ? AND is_read = 0', [$other_user_id, $current_user->id]);
                    foreach ($unread as $msg) {
                        $msg->is_read = 1;
                        $msg->read_at = date('Y-m-d H:i:s');
                        R::store($msg);
                    }
                    ?>
                    
                    <div class="card border-0 shadow-sm chat-container">
                        <div class="card-header border-bottom">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($other_user->first_name . ' ' . $other_user->last_name); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($other_user->city); ?></small>
                        </div>
                        
                        <div class="messages-area" id="messagesArea">
                            <?php foreach ($messages as $message): ?>
                                <div class="message <?php echo $message->from_user_id === $current_user->id ? 'sent' : 'received'; ?>">
                                    <div class="message-content">
                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($message->content)); ?></p>
                                        <small><?php echo date('H:i', strtotime($message->created_at)); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="card-footer border-top">
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="action" value="send_message">
                                <input type="hidden" name="to_user_id" value="<?php echo $other_user_id; ?>">
                                <input type="text" class="form-control" name="content" placeholder="Type a message..." required>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i></button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm d-flex align-items-center justify-content-center" style="height: 600px;">
                        <div class="text-center">
                            <i class="bi bi-chat-dots" style="font-size: 5rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Select a conversation to start messaging</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>