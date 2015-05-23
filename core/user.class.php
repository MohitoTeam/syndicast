<?php

class user {

    private $pdo;

    /*
     * @method construct
     * @Arguments: (object) $pdo
     * @Description: Method sets pdo object  object to private variable
     */

    public function __construct($pdo) {
        if (isset($pdo) && is_object($pdo))
            $this->pdo = $pdo;
    }

    /*
     * @method construct
     * @Arguments: none
     * @Description: Method returns info about user from database 
     */

    public function getUserData() {
        if (isset($_SESSION['login'])) {
            $data = array('login' => $_SESSION['login'], 'id' => $_SESSION['id']);
            $data = array_map('strip_tags', $data);

            if (validator::isEmpty($_SESSION['login']) || validator::isEmpty($_SESSION['id'])) {
                return false;
            }

            $stmt = $this->pdo->prepare('SELECT id, ip, login, user_agent, date, role FROM users WHERE id = :id AND login = :login');
            $stmt->bindParam(':login', $data['login'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    }

    public function getRadioMenu() {
        $menu = '<nav><ul>Ogolne:';
        $menu .= '<li><a href="?page=new">Najnowsze</a></li>';
        $menu .= '<li><a href="?page=podcasts_r">Podcasty</a></li>';
        $menu .= '<li><a href="?page=promotes_tracks_r">Promowane utwory</a></li>';
        $menu .= '<li><a href="?page=news_r">News</a></li>';
        $menu .= '<br/> Wsparcie: <br/>';
        $menu .= '<li><a href="?page=production_r">Produkcja</a></li>';
        $menu .= '<li><a href="?page=promotion_r">Promocja</a></li>';
        $menu .= '<li><a href="?page=autoftp_r">AutoFTP</a></li>';
        $menu .= '<li><a href="?page=faq_r">F.A.Q.</a></li>';
        $menu .= '<br/> Ustawienia: <br/>';
        $menu .= '<li><a href="?page=settings_r">Radio</a></li>';
        $menu .= '<li><a href="?page=assistant_r">Asystent</a></li>';
        $menu .= '<li><a href="?page=account_r">Konto</a></li>';
        $menu .= '<li><a href="?page=ftp_r">Dane FTP</a></li>';
        $menu .= '</ul></nav>';

        return $menu;
    }

    public function getArtistMenu() {
        $menu = '<nav><ul>Ogolne:';
        $menu .= '<li><a href="?page=distribution_u">Dystrybucja</a></li>';
        $menu .= '<li><a href="?page=promotion_tracks_r">Promocja utworow</a></li>';
        $menu .= '<li><a href="?page=finance">Finanse</a></li>';
        $menu .= '<br/> Wsparcie: <br/>';
        $menu .= '<li><a href="?page=production_r">Produkcja</a></li>';
        $menu .= '<li><a href="?page=promotion_r">Promocja</a></li>';
        $menu .= '<li><a href="?page=faq_r">F.A.Q.</a></li>';
        $menu .= '<br/> Ustawienia: <br/>';
        //$menu .= '<li><a href="?page=settings_r">Radio</a></li>';
        //$menu .= '<li><a href="?page=assistant_r">Asystent</a></li>';
        //$menu .= '<li><a href="?page=account_r">Konto</a></li>';
        //$menu .= '<li><a href="?page=ftp_r">Dane FTP</a></li>';
        $menu .= '</ul></nav>';

        return $menu;
    }

}

?>