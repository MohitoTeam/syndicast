<?php

class podcasts {

    private $pdo;

    public function __construct($pdo) {
        if (isset($pdo) && is_object($pdo))
            $this->pdo = $pdo;
    }

    public function get_promote_podcasts() {
        $approve = '1';
        $promotion = '1';
        $stmt = $this->pdo->prepare('SELECT id, artist_id, title, img, length, bitrate FROM podcasts WHERE  approve = :approve AND promotion=1');
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $final = '<script src="//api.html5media.info/1.1.8/html5media.min.js"></script>';

        foreach ($result as $tmp) {
            $stmt = $this->pdo->prepare('SELECT login FROM users WHERE id = :id');
            $stmt->bindParam(':id', $tmp['artist_id'], PDO::PARAM_STR);
            $stmt->execute();

            $result2 = $stmt->fetch();
            $final .= $tmp['title'] . "<br>";
            $final .= '<img src="podcasts/' . $result2['login'] . '/' . $tmp['img'] . '.jpg" height=200px width=200px> <br> 
                <audio src="podcasts/' . $result2['login'] . '/' . $tmp['img'] . '.mp3" controls preload></audio><br><br>
';
        }
        return $final;
    }

    public function get_my_podcasts() {

        $approve = '1';
        $stmt = $this->pdo->prepare('SELECT title, img, length, bitrate FROM podcasts WHERE artist_id = :id AND approve = :approve');
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $final = '<script src="//api.html5media.info/1.1.8/html5media.min.js"></script>';

        foreach ($result as $tmp) {
            $final .= $tmp['title'] . "<br>";
            $final .= '<img src="podcasts/' . $_SESSION['login'] . '/' . $tmp['img'] . '.jpg" height=200px width=200px> <br> 
                <audio src="podcasts/' . $_SESSION['login'] . '/' . $tmp['img'] . '.mp3" controls preload></audio><br><br>
';
        }
        return $final;
    }

    public function get_my_tracks() {
        $approve = '1';
        $stmt = $this->pdo->prepare('SELECT title, img, length, bitrate FROM mp3 WHERE artist_id = :id AND approve = :approve');
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $final = '<script src="//api.html5media.info/1.1.8/html5media.min.js"></script>';

        foreach ($result as $tmp) {
            $final .= $tmp['title'] . "<br>";
            $final .= '<img src="mp3s/' . $_SESSION['login'] . '/' . $tmp['img'] . '.jpg" height=200px width=200px> <br> 
                <audio src="mp3s/' . $_SESSION['login'] . '/' . $tmp['img'] . '.mp3" controls preload></audio><br><br>
';
        }
        return $final;
    }

    public function get_archives_tracks() {

        $approve = '1';
        $stmt = $this->pdo->prepare('SELECT title, img, length, bitrate FROM mp3 WHERE artist_id = :id AND approve = :approve AND promoted=:approve');
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $final = '<script src="//api.html5media.info/1.1.8/html5media.min.js"></script>';

        foreach ($result as $tmp) {
            $final .= $tmp['title'] . "<br>";
            $final .= '<img src="mp3s/' . $_SESSION['login'] . '/' . $tmp['img'] . '.jpg" height=200px width=200px> <br> 
                <audio src="mp3s/' . $_SESSION['login'] . '/' . $tmp['img'] . '.mp3" controls preload></audio><br><br>
';
        }
        return $final;
    }

    public function get_podcasts() {
        $approve = '1';
        $stmt = $this->pdo->prepare('SELECT podcast_id FROM subscribed_podcasts WHERE user_id = :id');
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $tmp) {
            $tab[] = $tmp['podcast_id'];
        }

        $stmt = $this->pdo->prepare('SELECT * FROM podcasts WHERE approve = :approve');
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $tmp) {
            $tab_all[] = $tmp['id'];
        }

        foreach ($tab_all as $single) {
            if (!in_array($single, $tab)) {
                $final[] = $single;
            }
        }

        $final2 = '<script src="//api.html5media.info/1.1.8/html5media.min.js"></script>';
        $final2 .= 'Sortuj wg: <br> <a href="index.php?page=new_r_podcasts&sort=title"> tytulu </a>';
        $allowed = array('title', 'artist_id');

        if (isset($_GET['sort']) && ($_GET['sort'] != NULL) && in_array($_GET['sort'], $allowed)) {
            $sort_by = 'ORDER BY ' . $_GET['sort'];
            //echo 'lol';
        }

        foreach ($final as $tmp) {
            $approve = '1';
            $stmt = $this->pdo->prepare("SELECT artist_id, title, img, length, bitrate FROM podcasts WHERE id = :id AND approve = :approve");
            //print_r ($stmt);
            $stmt->bindParam(":id", $tmp, PDO::PARAM_STR);
            $stmt->bindParam(":approve", $approve, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // bez fetch_assoc

            $stmt = $this->pdo->prepare('SELECT login FROM users WHERE id = :id');
            $stmt->bindParam(':id', $result[0]['artist_id'], PDO::PARAM_STR);
            $stmt->execute();

            $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $t[] = $result;


            $final2 .= $result[0]['title'] . "<br>";
            $final2 .= '<img src="podcasts/' . $result2[0]['login'] . '/' . $result[0]['img'] . '.jpg" height=200px width=200px> <br> 
                <audio src="podcasts/' . $result2[0]['login'] . '/' . $result[0]['img'] . '.mp3" controls preload></audio><br><br>
';
        }
        /*
          foreach ($t[0][0] as $single){
          $x[] = $single;
          //echo $single;
          }

          print_r ($x);
          //asort ($t[0][0]);
          //print_r ($t);
          echo '---------------------------------------------------------------';
          //print_r ($t[0]);
         * */


        return $final2;
    }

}
