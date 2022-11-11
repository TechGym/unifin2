<?php
class GeneralView extends General {
    private $username;
    private $user_id;
    private $country;
    private $email;

    public function getUserFromCookie($cookie) {
        return $this->fetchUserWithCookie($cookie);
    }

    public function setGeneralView($info) {
        $this->firstname = $info['firstname'];
        $this->user_id = $info['user_id'];
        $this->country = $info['country'];
        $this->email = $info['email'];
    }

    public function getUsername() {
        return $this->username;
    }

    public function fetchAllMessages() {
        return $this->fetchMessages();
    }
    public function fetchStatus() {
        return $this->fetchUsersStatus($this->email);
    }
}
