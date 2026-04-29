<?php
/**
 * GlobePair - Handle Message Sending
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    requireLogin();
    
    $to_user_id = intval($_POST['to_user_id'] ?? 0);
    $content = sanitize($_POST['content'] ?? '');
    
    $to_user = R::load('user', $to_user_id);
    
    if ($to_user_id && !empty($content) && $to_user->id) {
        if (!canSendMessage($current_user, $to_user)) {
            $_SESSION['error'] = 'Payment required to send messages';
            $_SESSION['message_redirect'] = $to_user_id;
            header('Location: ?action=payment_required');
            exit;
        }
        
        $message = R::dispense('message');
        $message->from_user_id = $current_user->id;
        $message->to_user_id = $to_user_id;
        $message->content = $content;
        $message->is_read = 0;
        $message->created_at = date('Y-m-d H:i:s');
        
        R::store($message);
        $_SESSION['success'] = 'Message sent!';
        header('Location: ?action=chat&user_id=' . $to_user_id);
        exit;
    }
}
?>