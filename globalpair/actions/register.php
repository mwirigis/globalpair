<?php
/**
 * GlobePair - Handle Registration
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $email = sanitize($_POST['email'] ?? '');
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $city = sanitize($_POST['city'] ?? '');
    $birth_date = $_POST['birth_date'] ?? '';
    $gender = sanitize($_POST['gender'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    
    $errors = [];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    if (empty($first_name) || empty($last_name)) {
        $errors[] = 'First and last name are required';
    }
    
    if (empty($errors)) {
        $existing = R::findOne('user', 'email = ?', [$email]);
        if ($existing) {
            $errors[] = 'Email already registered';
        }
    }
    
    $profile_image = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = handlePhotoUpload($_FILES['profile_photo']);
        if (is_array($upload_result) && isset($upload_result['error'])) {
            $errors[] = $upload_result['error'];
        } elseif (is_string($upload_result)) {
            $profile_image = $upload_result;
        }
    }
    
    if (empty($errors)) {
        $user = R::dispense('user');
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->city = $city;
        $user->birth_date = $birth_date;
        $user->gender = $gender;
        $user->bio = $bio;
        $user->profile_image = $profile_image;
        $user->user_role = 'regular';
        $user->is_admin = 0;
        $user->status = 'active';
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        $user_id = R::store($user);
        
        if ($profile_image) {
            $old_path = __DIR__ . '/../' . $profile_image;
            $extension = pathinfo($profile_image, PATHINFO_EXTENSION);
            $new_filename = 'user_' . $user_id . '_' . time() . '.' . $extension;
            $new_path = UPLOAD_DIR . $new_filename;
            
            if (rename($old_path, $new_path)) {
                $user->profile_image = 'uploads/' . $new_filename;
                R::store($user);
            }
        }
        
        $_SESSION['success'] = 'Registration successful! Please login.';
        header('Location: ?action=login');
        exit;
    } else {
        if ($profile_image && file_exists(__DIR__ . '/../' . $profile_image)) {
            unlink(__DIR__ . '/../' . $profile_image);
        }
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'city' => $city,
            'birth_date' => $birth_date,
            'gender' => $gender,
            'bio' => $bio
        ];
    }
}
?>