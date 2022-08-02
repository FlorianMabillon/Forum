<?php

require('actions/database.php');


// Validation du formulaire
if (isset($_POST['validate'])) {

    // Vérifier si l'utilisateur a bien complété tous les champs
    if (!empty($_POST['pseudo']) && !empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['password'])) {
        
        // Les données de l'utilisateur
        $user_pseudo = htmlspecialchars($_POST['pseudo']);
        $user_lastname = htmlspecialchars($_POST['lastname']);
        $user_firstname = htmlspecialchars($_POST['firstname']);
        $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Verifié si l'utilisateur existe déjà sur le site
        $checkIfUserAlreadyExists = $bdd->prepare('SELECT pseudo FROM users WHERE pseudo=?');
        $checkIfUserAlreadyExists->execute(array($user_pseudo));

        if ($checkIfUserAlreadyExists->rowCount() == 0) {
            
            //Insérer l'utilisateur dans la bdd
            $insertUserOnWebsite = $bdd->prepare('INSERT INTO users(pseudo, lastname, firstname, password) VALUES(?, ?, ?, ?)');
            $insertUserOnWebsite->execute(array($user_pseudo, $user_lastname, $user_firstname, $user_password));

            // Récupérer les informations de l'utilisateur
            $getInfosOfThisUserReq = $bdd->prepare('SELECT id, pseudo, lastname, firstname FROM users WHERE lastname = ? AND fistname = ? AND pseudo = ?');
            $getInfosOfThisUserReq->execute(array($user_lastname, $user_firstname, $user_pseudo));

            $usersInfos = $getInfosOfThisUserReq->fetch();

            // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables globales sessions
            $_SESSION['auth'] = true;
            // $_SESSION['id'] = $usersInfos['id'];
            $_SESSION['lastname'] = $usersInfos['lastname'];
            $_SESSION['firstname'] = $usersInfos['firstname'];
            $_SESSION['pseudo'] = $usersInfos['pseudo'];


            // Rediriger l'utilisateur vers la page d'acceuil
            header('Location: index.php');

        }else{
            $errorMsg = 'L\'utilisateur existe déjà sur le site';
        }

    }else{
        $errorMsg = 'Veuillez compléter tous les champs...';
    }
}