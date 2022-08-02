<?php
require('actions/database.php');

// Validation du formulaire
if (isset($_POST['validate'])) {

    // Vérifier si l'utilisateur a bien complété tous les champs
    if (!empty($_POST['pseudo']) && !empty($_POST['password'])) {
        
        // Les données de l'utilisateur
        $user_pseudo = htmlspecialchars($_POST['pseudo']);
        $user_password = htmlspecialchars($_POST['password']);


        // Vérifier si l'utilisateur existe
        $checkIfUsersExists = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
        $checkIfUsersExists->execute(array($user_pseudo));

        if ($checkIfUsersExists->rowCount() > 0) {
            
            // Récupérer les données de l'utilisateur
            $usersInfos = $checkIfUsersExists->fetch();

            // Vérifier si le mot de passe est correct
            if (password_verify($user_password, $usersInfos['password'])) {
                
            // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables globales sessions
            $_SESSION['auth'] = true;
            $_SESSION['lastname'] = $usersInfos['lastname'];
            $_SESSION['firstname'] = $usersInfos['firstname'];
            $_SESSION['pseudo'] = $usersInfos['pseudo'];

            // Rediriger l'utilisateur sur la page d'acceuil
            header('Location: index.php');
            }else{
                $errorMsg = "Mot de passe incorrect";
            }

        }else{
            $errorMsg = 'Votre pseudo est incorrect...';

        }

        }else{
        $errorMsg = 'Veuillez compléter tous les champs...';
    }
}