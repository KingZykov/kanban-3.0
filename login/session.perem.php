<?php  
 function setSessionData($statement, $id_user, $user_form, $role) {
    while ($id = $statement->fetch(PDO::FETCH_ASSOC)) {
        $id_user = $id['id_user'];
        $_SESSION['id_user'] = $id_user;
        $_SESSION['user'] = $user_form;
        $_SESSION['role'] = $role;
    }
 }
?>