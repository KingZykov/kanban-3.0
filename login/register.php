<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: content.php');
    die();
}

require '../db/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
/*     $user_form = filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING);
    $password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
    $password2_form = filter_var(htmlspecialchars($_POST['password2']), FILTER_SANITIZE_STRING); */
    $user_form = htmlspecialchars($_POST['user'], FILTER_SANITIZE_SPECIAL_CHARS);
    $password_form = htmlspecialchars($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $password2_form = htmlspecialchars($_POST['password2'], FILTER_SANITIZE_SPECIAL_CHARS);
    $errors = [];

    if (empty($user_form) || empty($password_form) || empty($password2_form)) {
        $errors[] = 'Please fill in all the required fields.';
    } else {
        $database = new Database();
        $connection = $database->connection();

        $statement = $connection->prepare('SELECT id_user FROM users WHERE user = :user LIMIT 1');
        $statement->execute([':user' => $user_form]);
        if ($statement->fetch()) {
            $errors[] = 'Sorry, the username already exists.';
        }

        if ($password_form !== $password2_form) {
            $errors[] = 'The password confirmation does not match.';
        } else {
            $password_form = password_hash($password_form, PASSWORD_DEFAULT);
        }
    }

    if (empty($errors)) {
        $statement = $connection->prepare('INSERT INTO users (user, password, role) VALUES (:user, :password, "user")');
        $statement->execute([
            ':user' => $user_form,
            ':password' => $password_form
        ]);
        header('Location: login.php');
        die();
    }
}

require '../views/register.view.php';
?>