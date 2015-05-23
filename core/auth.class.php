<?php

class auth {

    private $pdo = null;
    private $session = null;
    public $host = 'http://127.0.0.1';
    public $dir = '/syndication/';

    /*
     * @method construct
     * @Arguments: (object) $pdo
      (object) $session
     * @Description: Method sets pdo object and session object to private variable
     */

    public function __construct($pdo, $session) {
        if (isset($pdo) && is_object($pdo)) {
            $this->pdo = $pdo;
        }

        if (isset($session) && is_object($session)) {
            $this->session = $session;
        }
    }

    public function send_mail2($address, $subject, $body) {
        $headers = "Reply-to: Syndication Project <syndication@127.0.0.1>" . PHP_EOL;
        $headers .= "From: Syndication Project <syndication@127.0.0.1>" . PHP_EOL;
        $headers .= "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=iso-8859-2" . PHP_EOL;

        $body_final = '<html> 
        <head> 
            <title>Wiadomoœæ e-mail</title> 
        </head>
        
        <body>
            <p><b>Kod aktywacyjny</b>: ' . $this->host . $this->dir . 'index.php?page=activation_account&email=' . $address . '&activation_code=' . $body . '</p>
        </body>
        </html>';

        if (mail($address, $subject, $body_final, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function send_mail($address, $subject, $body, $nick) {
        require_once('class.phpmailer.php');
        require_once('class.smtp.php');
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->From = "syndication@onet.pl";
        $mail->FromName = "Syndication Project";
        $mail->AddReplyTo('syndication@onet.pl', 'Syndication Project');
        $mail->Host = "smtp.poczta.onet.pl";
        $mail->Mailer = "smtp";
        $mail->SMTPAuth = true;
        $mail->Username = "syndication@onet.pl";
        $mail->Password = "syndication_test1";
        $mail->Port = 465;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($address, $nick);
        if ($mail->Send()) {    //sprawdzenie wys³ania, jeœli wiadomoœæ zosta³a pomyœlnie wys³ana
            echo 'E-mail zosta³ wys³any'; //wyœwietl ten komunikat
        } else {    //w przeciwnym wypadku
            echo 'E-mail nie móg³ zostaæ wys³any';    //wyœwietl nastêpuj¹cy
        }

        return;
    }

    public function login($data) {  //active - login
        if (!is_array($data)) {
            return false;
        } elseif (!array_key_exists('login', $data) || !array_key_exists('password', $data)) {
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT active as activ FROM users WHERE login = :login');
        $stmt->bindParam(':login', $data['login'], PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        if ($result['activ'] == 0) {
            return core::$text[11];
        } else {
            $data = array_map('strip_tags', $data);

            if (validator::isEmpty($data['login']) || validator::isEmpty($data['password'])) {
                return core::$text[0];
            }

            $data['password'] = hash('sha512', $data['password']);

            $stmt = $this->pdo->prepare('SELECT count(id) as number, id FROM users WHERE login = :login AND password = :password');
            $stmt->bindParam(':login', $data['login'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch();

            if ($result['number'] == 1) {
                $this->session->setSession(array('login' => $data['login'], 'id' => $result['id']), true);
                return array(0 => core::$text[1], 1 => true);
            } else {
                return core::$text[2];
            }
        }
    }

    public function activation($data) {
        $data = array_map('strip_tags', $data);

        $stmt = $this->pdo->prepare('SELECT count(id) as number, login, id FROM users WHERE email = :email AND activation_hash = :activation_hash');
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':activation_hash', $data['activation_code'], PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        $stmt = $this->pdo->prepare('UPDATE users SET active=:active WHERE email=:email');
        $stmt->bindValue(':active', '1', PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->execute();

        if ($result['number'] == 1) {
            $this->session->setSession(array('login' => $result['login'], 'id' => $result['id']), true);
            $tmp = $this->host . $this->dir . 'index.php?page=user_panel';
            return $tmp;
        } else {
            return core::$text[10];
        }
    }

    //Rejestracja dla Radia
    public function register($data) {
        if (!is_array($data)) {
            return false;
        } elseif (!array_key_exists('login', $data) || !array_key_exists('password', $data) || !array_key_exists('password2', $data) || !array_key_exists('email', $data)) {
            return false;
        }

        $data = array_map('strip_tags', $data);
        $data = array_map('trim', $data);

        if (validator::isEmpty($data['login']) || validator::isEmpty($data['password']) || validator::isEmpty($data['email']) || validator::isEmpty($data['password2'])) {
            return core::$text[0];
        } elseif (!validator::email($data['email'])) {
            return core::$text[3];
        } else if ($this->UserExists($data['login'])) {
            return core::$text[4];
        } else if ($this->EmailExists($data['email'])) {
            return core::$text[5];
        } else if ($this->CheckPasswords($data['password'], $data['password2'])) {
            return core::$text[8];
        }

        $data['password'] = hash('sha512', $data['password']);

        $additional = array('IP' => $_SERVER['REMOTE_ADDR'], 'user_agent' => $_SERVER['HTTP_USER_AGENT']);
        $additional = array_map('strip_tags', $additional);
        $additional = array_map('trim', $additional);
        $activation_hash = md5(uniqid());

        // Wysy³anie przez PHPMailer - nie dzia³a na wszystkich serwerach pocztowych
        // W funkcji send_mail potrzebna konfiguracja
        // $mail = $this->send_mail($data['email'], "Potwierdzenie rejestracji - Syndication Project", $activation_hash, $data['login']);
        // Wysy³anie przez mail()

        if ($this->send_mail2($data['email'], 'Potwierdzenie Rejestracji - Syndication Project', $activation_hash)) {
            echo 'email zosta³ wys³any';
        } else {
            echo 'Email nie zosta³ wys³any';
        }

        $stmt = $this->pdo->prepare('INSERT INTO users VALUES ("", :login, :password, :email, "' . $additional['IP'] . '", "' . $additional['user_agent'] . '", now(), "", :activation_hash, :role)');
        $stmt->bindParam(':login', $data['login'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':activation_hash', $activation_hash, PDO::PARAM_STR);
        $stmt->bindParam(':role', '2', PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return array(0 => core::$text[6], 1 => true);
        } else {
            return core::$text[7];
        }
    }

    //Rejestracja dla artysty
    public function register_artist($data) {
        if (!is_array($data)) {
            return false;
        } elseif (!array_key_exists('login', $data) || !array_key_exists('password', $data) || !array_key_exists('password2', $data) || !array_key_exists('email', $data)) {
            return false;
        }

        $data = array_map('strip_tags', $data);
        $data = array_map('trim', $data);

        if (validator::isEmpty($data['login']) || validator::isEmpty($data['password']) || validator::isEmpty($data['email']) || validator::isEmpty($data['password2'])) {
            return core::$text[0];
        } elseif (!validator::email($data['email'])) {
            return core::$text[3];
        } else if ($this->UserExists($data['login'])) {
            return core::$text[4];
        } else if ($this->EmailExists($data['email'])) {
            return core::$text[5];
        } else if ($this->CheckPasswords($data['password'], $data['password2'])) {
            return core::$text[8];
        }

        $data['password'] = hash('sha512', $data['password']);

        $additional = array('IP' => $_SERVER['REMOTE_ADDR'], 'user_agent' => $_SERVER['HTTP_USER_AGENT']);
        $additional = array_map('strip_tags', $additional);
        $additional = array_map('trim', $additional);
        $activation_hash = md5(uniqid());
        $role = '1';

        // Wysy³anie przez PHPMailer - nie dzia³a na wszystkich serwerach pocztowych
        // W funkcji send_mail potrzebna konfiguracja
        // $mail = $this->send_mail($data['email'], "Potwierdzenie rejestracji - Syndication Project", $activation_hash, $data['login']);
        // Wysy³anie przez mail()

        if ($this->send_mail2($data['email'], 'Potwierdzenie Rejestracji - Syndication Project', $activation_hash)) {
            echo 'email zosta³ wys³any';
        } else {
            echo 'Email nie zosta³ wys³any';
        }

        $stmt = $this->pdo->prepare('INSERT INTO users VALUES ("", :login, :password, :email, "' . $additional['IP'] . '", "' . $additional['user_agent'] . '", now(), "", :activation_hash, :role)');
        $stmt->bindParam(':login', $data['login'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':activation_hash', $activation_hash, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        //$stmt->bindParam(':podcast_hash', $activation_hash, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            mkdir('podcasts/' . $data['login'], 0777);
            mkdir('mp3s/' . $data['login'], 0777);            
            return array(0 => core::$text[6], 1 => true);
        } else {
            return core::$text[7];
        }
    }

    public function add_podcast_database($name, $id) {

        $approve = '0';
        $stmt = $this->pdo->prepare('INSERT INTO podcasts VALUES ("", :artist_id, now(), :img, "", :approve, "","")');
        $stmt->bindParam(':artist_id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':date', date('j f Y h:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':img', $name, PDO::PARAM_STR);
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    }
    
    public function add_track_database($name, $id) {

        $approve = '0';
        $stmt = $this->pdo->prepare('INSERT INTO mp3 VALUES ("", :artist_id, now(), :img, "", :approve, "","","")');
        $stmt->bindParam(':artist_id', $id, PDO::PARAM_STR);
        //$stmt->bindParam(':date', date('j f Y h:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':img', $name, PDO::PARAM_STR);
        $stmt->bindParam(':approve', $approve, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    }

    public function add_final($title, $login) {
        if (file_exists('podcasts/' . $login . '/controller.txt')) {
            $handler = fopen('podcasts/' . $login . '/controller.txt', 'r');
            $name = fread($handler, 32);
        } else {
            return core::$text[13];
        }

        $mp3file = new MP3File('podcasts/' . $login . '/' . $name . '.mp3');
        $duration2 = $mp3file->getDuration();
        $bitrate = $this->getMP3BitRateSampleRate('podcasts/' . $login . '/' . $name . '.mp3');

        if ($duration2 > 7900 || $bitrate['bitRate'] < 300) {
            return core::$text[14];
        }

        list($width_orig, $height_orig) = getimagesize('podcasts/' . $login . '/' . $name . '.jpg');

        if ($width_orig < 400 || $height_orig < 400) {
            return core::$text[15];
        }

        if ($width_orig > 1000 || $height_orig > 1000) {
            return core::$text[17];
        }

        if ($width_orig > 400 && $width_orig < 1000 && $height_orig > 400 && $height_orig < 1000) {
            if ($width_orig > $height_orig || $width_orig == $height_orig) {
                $size = $height_orig;
            } else {
                $size = $width_orig;
            }
        }

        $image_p = imagecreatetruecolor($size, $size);
        $image = imagecreatefromjpeg('podcasts/' . $login . '/' . $name . '.jpg');
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $size, $size, $width_orig, $height_orig);
        imagejpeg($image_p, 'podcasts/' . $login . '/' . $name . '.jpg', 100);

        $approve = '1';
        $stmt = $this->pdo->prepare('UPDATE podcasts SET approve=:approve, title=:title, length=:length, bitrate=:bitrate WHERE img=:img');
        $stmt->bindValue(':approve', $approve, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':img', $name, PDO::PARAM_STR);
        $stmt->bindValue(':length', $duration2, PDO::PARAM_STR);
        $stmt->bindValue(':bitrate', $bitrate['bitRate'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return array(0 => core::$text[12], 1 => true);
        } else {
            return core::$text[13];
        }
    }
    
    public function add_track_final($title, $login) {
        if (file_exists('mp3s/' . $login . '/controller.txt')) {
            $handler = fopen('mp3s/' . $login . '/controller.txt', 'r');
            $name = fread($handler, 32);
        } else {
            return core::$text[13];
        }

        $mp3file = new MP3File('mp3s/' . $login . '/' . $name . '.mp3');
        $duration2 = $mp3file->getDuration();
        $bitrate = $this->getMP3BitRateSampleRate('mp3s/' . $login . '/' . $name . '.mp3');

        if ($duration2 > 7900 || $bitrate['bitRate'] < 300) {
            return core::$text[14];
        }

        list($width_orig, $height_orig) = getimagesize('mp3s/' . $login . '/' . $name . '.jpg');

        if ($width_orig < 400 || $height_orig < 400) {
            return core::$text[15];
        }

        if ($width_orig > 1000 || $height_orig > 1000) {
            $size = 1000;
        }

        if ($width_orig > 400 && $width_orig < 1000 && $height_orig > 400 && $height_orig < 1000) {
            if ($width_orig > $height_orig || $width_orig == $height_orig) {
                $size = $height_orig;
            } else {
                $size = $width_orig;
            }
        }

        $image_p = imagecreatetruecolor($size, $size);
        $image = imagecreatefromjpeg('mp3s/' . $login . '/' . $name . '.jpg');
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $size, $size, $width_orig, $height_orig);
        imagejpeg($image_p, 'mp3s/' . $login . '/' . $name . '.jpg', 100);

        $approve = '1';
        $stmt = $this->pdo->prepare('UPDATE mp3 SET approve=:approve, title=:title, length=:length, bitrate=:bitrate WHERE img=:img');
        $stmt->bindValue(':approve', $approve, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':img', $name, PDO::PARAM_STR);
        $stmt->bindValue(':length', $duration2, PDO::PARAM_STR);
        $stmt->bindValue(':bitrate', $bitrate['bitRate'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return array(0 => core::$text[12], 1 => true);
        } else {
            echo 'looool';
            return core::$text[13];
        }
    }

    private function UserExists($login) {
        $stmt = $this->pdo->prepare('SELECT count(id) as number FROM users WHERE login = :login LIMIT 1');
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result['number'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function CheckPasswords($password_one, $password_two) {
        if ($password_one != $password_two) {
            return true;
        } else {
            return false;
        }
    }

    private function EmailExists($email) {

        $stmt = $this->pdo->prepare('SELECT count(id) as number FROM users WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result['number'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        session_destroy();
        header('Location:index.php');
    }

    public function bad_extension() {
        return core::$text[16];
    }

    public function getMP3BitRateSampleRate($filename) {
        if (!file_exists($filename)) {
            return false;
        }

        $bitRates = array(
            array(0, 0, 0, 0, 0),
            array(32, 32, 32, 32, 8),
            array(64, 48, 40, 48, 16),
            array(96, 56, 48, 56, 24),
            array(128, 64, 56, 64, 32),
            array(160, 80, 64, 80, 40),
            array(192, 96, 80, 96, 48),
            array(224, 112, 96, 112, 56),
            array(256, 128, 112, 128, 64),
            array(288, 160, 128, 144, 80),
            array(320, 192, 160, 160, 96),
            array(352, 224, 192, 176, 112),
            array(384, 256, 224, 192, 128),
            array(416, 320, 256, 224, 144),
            array(448, 384, 320, 256, 160),
            array(-1, -1, -1, -1, -1),
        );
        $sampleRates = array(
            array(11025, 12000, 8000), //mpeg 2.5
            array(0, 0, 0),
            array(22050, 24000, 16000), //mpeg 2
            array(44100, 48000, 32000), //mpeg 1
        );
        $bToRead = 1024 * 12;

        $fileData = array('bitRate' => 0, 'sampleRate' => 0);
        $fp = fopen($filename, 'r');
        if (!$fp) {
            return false;
        }
        fseek($fp, -1 * $bToRead, SEEK_END);
        $data = fread($fp, $bToRead);

        $bytes = unpack('C*', $data);
        $frames = array();
        $lastFrameVerify = null;

        for ($o = 1; $o < count($bytes) - 4; $o++) {
            if (($bytes[$o] & 255) == 255 && ($bytes[$o + 1] & 224) == 224) {
                $frame = array();
                $frame['version'] = ($bytes[$o + 1] & 24) >> 3; //get BB (0 -> 3)
                $frame['layer'] = abs((($bytes[$o + 1] & 6) >> 1) - 4); //get CC (1 -> 3), then invert
                $srIndex = ($bytes[$o + 2] & 12) >> 2; //get FF (0 -> 3)
                $brRow = ($bytes[$o + 2] & 240) >> 4; 
                $frame['padding'] = ($bytes[$o + 2] & 2) >> 1;
                if ($frame['version'] != 1 && $frame['layer'] > 0 && $srIndex < 3 && $brRow != 15 && $brRow != 0 &&
                        (!$lastFrameVerify || $lastFrameVerify === $bytes[$o + 1])) {
                    //valid frame header
                    //calculate how much to skip to get to the next header
                    $frame['sampleRate'] = $sampleRates[$frame['version']][$srIndex];
                    if ($frame['version'] & 1 == 1) {
                        $frame['bitRate'] = $bitRates[$brRow][$frame['layer'] - 1]; //v1 and l1,l2,l3
                    } else {
                        $frame['bitRate'] = $bitRates[$brRow][($frame['layer'] & 2 >> 1) + 3]; //v2 and l1 or l2/l3 (3 is the offset in the arrays)
                    }

                    if ($frame['layer'] == 1) {
                        $frame['frameLength'] = (12 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding']) * 4;
                    } else {
                        $frame['frameLength'] = 144 * $frame['bitRate'] * 1000 / $frame['sampleRate'] + $frame['padding'];
                    }

                    $frames[] = $frame;
                    $lastFrameVerify = $bytes[$o + 1];
                    $o += floor($frame['frameLength'] - 1);
                } else {
                    $frames = array();
                    $lastFrameVerify = null;
                }
            }
            if (count($frames) < 3) { //verify at least 3 frames to make sure its an mp3
                continue;
            }

            $header = array_pop($frames);
            $fileData['sampleRate'] = $header['sampleRate'];
            $fileData['bitRate'] = $header['bitRate'];

            break;
        }

        return $fileData;
    }

}

?>