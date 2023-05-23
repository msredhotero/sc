<?php

class Session {

    private $sessionName = 'user';

    public function __construct($sessionName)
    {
        $this->sessionName = $sessionName;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setCurrentUser($user) {
        $_SESSION['user'] = $user;
    }

    public function getCurrentUser($user) {
        return $_SESSION['user'];
    }

    public function closeSession() {
        session_unset();
        session_destroy();
    }

    public function exists() {
        return isset($_SESSION['user']);
    }
    
}

