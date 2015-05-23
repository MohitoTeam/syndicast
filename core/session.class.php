<?php

class session {

    /**
     * @method _construct()
     * @Description: method sets basic session datas(session_id, user_agent and ip).
     */
    public function __construct() {

        $this->setSession(array(
            'session_id' => $this->getSessionId(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $_SERVER['REMOTE_ADDR']
                ), true);
    }

    /**
     * @method basicExists()
     * @Description: method checks if basic session data exists
     */
    public function basicExists() {
        if ($this->exists('session_id') && $this->exists('user_agent') && $this->exists('ip'))
            return true;
        else
            return false;
    }

    /*
     * @method getSessionId
     * @Arguments: none
     * @Description: Method return session ID if exists.
     */

    public function getSessionId() {

        if (session_id())
            return session_id();
        else
            return false;
    }

    /*
     * @method regenerateID
     * @Arguments: (boolean) $type
     * @Description: Method regenerate ID of session.
      If $type in set on true, old session ID will be removed
      Default it is set on true
     */

    public function regenerateId($type = true) {

        if ($type == true)
            return session_regenerate_id(true);
        else
            return session_regenerate_id();
    }

    /*
     * @method read_session
     * @Arguments: (string)  $key
      (boolean) $multi
     * @Description: Method returns value of sesson given in variable $key.
      If $multi is set on true, method will return whole table with session store
     */

    public function readSession($key, $multi = false) {
        if ($multi == false)
            return $_SESSION[$key];
        else {
            return $_SESSION;
        }
    }

    /*
     * @method setSession
     * @Arguments: (array)  $data
      (boolean) $multi
     * @Description: Method set new session data. Default it create just one session variable from variable $data (array)
      When $multi is set on true, method will create more than one session variable using variable $data.
     */

    public function setSession($data = array(), $multi = false) {

        if ($multi == false)
            $_SESSION[array_keys($data)[0]] = array_values($data)[0];
        else {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    /*
     * @method exists
     * @Arguments: (string)  $name
     * @Description: Method checks whether session with key like name exists.
      If it exists return true, else false.
     */

    public function exists($key) {
        return array_key_exists($key, $_SESSION) ? true : false;
    }

    /*
     * @method remove
     * @Arguments: (boolean)  $global
     * @Description: Method deletes whole data connected with session but not global variables. It is not deletes session cookie, as well.
      If $global is set on true, method will delete all data.

     */

    public function remove($global = false) {
        if ($type == false)
            session_destroy();
        else
            session_unset();
    }

    /*
     * @method removeOne
     * @Arguments: (string)  $key
     * @Description: Method removes just one session variable with $key given in arguments of method.                
     */

    public function removeOne($key) {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
        else
            return false;
    }

}

?>