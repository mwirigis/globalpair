<?php
/**
 * GlobePair - Handle Logout
 */

if ($action === 'logout') {
    session_destroy();
    header('Location: ?action=home');
    exit;
}
?>