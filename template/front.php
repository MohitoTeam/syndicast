<?php

if (isset($_GET['page'])) {
    $page = strip_tags($_GET['page']);
} else {
    $page = null;
}


switch ($page) {
    case 'main':
        require 'main.php';
        break;

    case 'register':
        require 'register.php';
        break;

    case 'register_artist':
        require 'register_artist.php';
        break;

    case 'new':
        require 'new.php';
        break;

    case 'podcasts_r':
        require 'podcasts_r.php';
        break;
    
    case 'all_podcasts':
        $podcasts = new podcasts($pdo, $session);
        $p = $podcasts->get_podcasts();
        print_r ($p);
        break;
    
    case 'promote_podcasts':
        $promote_podcasts = new podcasts ($pdo, $session);
        $result = $promote_podcasts->get_promote_podcasts();
        print_r($result);
        break;

    case 'my_podcasts':
        $podcasts = new podcasts($pdo, $session);
        $result = $podcasts->get_my_podcasts();
        print_r($result);
        break;

    case 'promotion_tracks_r':
        require 'promotion_tracks_r.php';
        break;

    case 'news_r':
        require 'news.php';
        break;

    case 'production_r':
        require 'production.php';
        break;

    case 'promotion_r':
        require 'promotion.php';
        break;
    
    case 'finance':
        require 'finance.php';
        break;
    
    case 'tracks_archive':
        $podcasts = new podcasts ($pdo, $session);
        $result = $podcasts->get_archives_tracks();
        print_r ($result);
        break;
    
    case 'my_tracks':
        //require 'my_tracks.php';
        $podcasts = new podcasts($pdo, $session);
        $result = $podcasts -> get_my_tracks();
        print_r($result);
        break;

    case 'autoftp_r':
        require 'autoftp.php';
        break;

    case 'faq_r':
        require 'faq.php';
        break;

    case 'settings_r':
        require 'settings.php';
        break;

    case 'assistant_r':
        require 'assistant.php';
        break;

    case 'account_r':
        require 'account.php';
        break;

    case 'ftp_r':
        require 'ftp.php';
        break;

    case 'distribution_u':
        require 'distribution.php';
        break;

    case 'add_podcast':
        require 'add_podcast.php';
        break;

    case 'add_track':
        require 'add_track.php';
        break;
    
    case 'new_r_podcasts':
        //$r = new auth($pdo, null);
        $podcasts = new podcasts($pdo, $session);
        $p = $podcasts->get_podcasts();
        print_r ($p);
        break;

    case 'register_action': {
            $register = new auth($pdo, null);

            if (($_POST['login'] != null) && ($_POST['password'] != null) && ($_POST['email'] != null)) {
                $data = array('login' => $_POST['login'], 'password' => $_POST['password'], 'email' => $_POST['email'], 'password2' => $_POST['password2']);
                $result = $register->register($data);
                if (is_array($result) && $result[1] == true) {
                    echo $result[0];
                } else {
                    echo $result;
                }
            }
        }
        break;

    case 'register_artist_action': {
            $register = new auth($pdo, null);

            if (($_POST['login'] != null) && ($_POST['password'] != null) && ($_POST['email'] != null)) {
                $data = array('login' => $_POST['login'], 'password' => $_POST['password'], 'email' => $_POST['email'], 'password2' => $_POST['password2']);
                $result = $register->register_artist($data);
                if (is_array($result) && $result[1] == true) {
                    echo $result[0];
                } else {
                    echo $result;
                }
            }
        }
        break;

    case 'activation_account': {
            $activation = new auth($pdo, $session);

            if (($_GET['email'] != null) && ($_GET['activation_code'] != null)) {
                $data = array('email' => $_GET['email'], 'activation_code' => $_GET['activation_code']);
                $result = $activation->activation($data);
                header('refresh: 1; url=' . $result . '');
            }
        }
        break;

    case 'login';
        require 'login.php';
        break;

    case 'login_action': {
            $login = new auth($pdo, $session);
            $data = array('login' => $_POST['login'], 'password' => $_POST['password']);
            $result = $login->login($data);
            if (is_array($result) && $result[1] == true) {
                echo $result[0];
                header('Location:?page=user_panel');
            } else {
                echo $result;
            }
        }
        break;

    case 'add_podcast_action': {
            $tmp = new auth($pdo, $session);
            $allowed = array('mp3', 'jpg');

            if ($result['role'] == 1) {
                if (file_exists('podcasts/' . $result['login'] . '/controller.txt')) {
                    $handler = fopen('podcasts/' . $result['login'] . '/controller.txt', 'r');
                    $name = fread($handler, 32);
                } else {
                    $handler = fopen('podcasts/' . $result['login'] . '/controller.txt', 'w');
                    $name = md5(uniqid() . uniqid());
                    fwrite($handler, $name);
                }

                if (isset($_FILES['up2']) && $_FILES['up2']['error'] == 0) {
                    $extension = pathinfo($_FILES['up2']['name'], PATHINFO_EXTENSION);

                    if (!in_array(strtolower($extension), $allowed)) {
                        $final = $tmp->bad_extension();
                    } else {
                        move_uploaded_file($_FILES['up2']['tmp_name'], 'podcasts/' . $result['login'] . '/' . $name . '.' . $extension);
                        $final = $tmp->add_podcast_database($name, $result['id']);
                    }
                }
            } else {
                echo 'brak uprawnien';
            }
        }
        break;

    case 'add_track_action': {
            $tmp = new auth($pdo, $session);
            $allowed = array('mp3', 'jpg');

            if ($result['role'] == 1) {
                if (file_exists('mp3s/' . $result['login'] . '/controller.txt')) {
                    $handler = fopen('mp3s/' . $result['login'] . '/controller.txt', 'r');
                    $name = fread($handler, 32);
                } else {
                    $handler = fopen('mp3s/' . $result['login'] . '/controller.txt', 'w');
                    $name = md5(uniqid() . uniqid());
                    fwrite($handler, $name);
                }

                if (isset($_FILES['up2']) && $_FILES['up2']['error'] == 0) {
                    $extension = pathinfo($_FILES['up2']['name'], PATHINFO_EXTENSION);

                    if (!in_array(strtolower($extension), $allowed)) {
                        $final = $tmp->bad_extension();
                    } else {
                        move_uploaded_file($_FILES['up2']['tmp_name'], 'mp3s/' . $result['login'] . '/' . $name . '.' . $extension);
                        $final = $tmp->add_track_database($name, $result['id']);
                    }
                }
            } else {
                echo 'brak uprawnien';
            }
        }
        break;


    case 'logout': {
            $login = new auth($pdo);
            $login->logout();
        }
        break;

    case 'user_panel':
        require 'user_panel.php';
        break;

    case 'add_track_final_action': {
            $tmp = new auth($pdo, $session);
            $user = new user($pdo);
            $result = $user->getUserData();

            if ($_POST['track_name'] != null) {
                $add_final = $tmp->add_track_final($_POST['track_name'], $result['login']);

                if (is_array($add_final) && $add_final[1] == true) {
                    unlink('mp3s/' . $result['login'] . '/controller.txt');
                    echo $add_final[0];
                } else {
                    echo $add_final;
                }
            } else {
                echo 'nie wpisales tytulu utworu';
            }
        }
        break;

    default:
        require 'main.php';
        break;
}
?>