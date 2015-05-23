<!DOCTYPE html>
<html lang="<?php echo core::$config['system']['defaultLang']; ?>">
    <head>
        <meta charset="<?php echo core::$config['system']['charset']; ?>">
        <meta name="description" content="<?php echo core::$config['system']['defaultDescription']; ?>">
        <title><?php echo core::$config['system']['defaultTitle']; ?></title>
        <link href="css/style.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
        <link href="assets/css/style.css" rel="stylesheet" />
    </head>
    <body>
        <header>
            <h1><a href="index.php" title="Strona główna">Skrypt rejestracji i logowania</a></h1>
        </header>

        <section class="main-content">
            <div class="left-section">
                <nav>
                    <ul>
                        <li><a href="index.php">Strona główna</a></li>
                        <?php
                        if (!$session->basicExists() || !$session->exists('id')){
                            echo '<li><a href="?page=register">Rejestracja - Radio</a></li>'
                            . '<li><a href="?page=register_artist">Rejestracja - Artysta</a></li>'
                        . '<li><a href="?page=login">Logowanie</a></li>';}

else{                            echo '<li><a href="?page=user_panel">Panel użytkownika</a></li> <br/>';


                            $user = new user($pdo);
                            $result = $user->getUserData();

                            if ($result['role'] == 2) {
                                echo $menu = $user->getRadioMenu();
                            } elseif ($result['role'] == 1) {
                                echo $menu = $user->getArtistMenu();
                            }
                        }
                        //print_r($result);
                        ?>
                    </ul>
                </nav>
            </div>

            <div class="right-section">