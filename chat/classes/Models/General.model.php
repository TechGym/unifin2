<?php
class General extends Dbh {
    protected function fetchUserWithCookie($cookie) {
        $sql = "SELECT u.user_id,u.firstname, u.email, u.country
        FROM users u
        JOIN cookies c
        ON u.email = c.email
        WHERE  c.cookie = '$cookie'";
        $result = $this->connectDB()->query($sql);
        $result = $result->fetch();
        if (!$result) {
            // clear cookies 
            setcookie(md5('logUser'), "", time() - (24 * 3600), "/");
            unset($_SESSION);
            return "dead";
        }
        $loggedInfo = $result;
        return $loggedInfo;
    }

    protected function fetchMessages() {
        $sql = "SELECT * FROM messages WHERE type = 'new' ORDER BY timestamp ASC LIMIT 250 ;";
        $stmt = $this->connectDB()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function fetchUsersStatus($email) {
        $sql = "SELECT status FROM membership WHERE user_email = '$email'";
        $stmt = $this->connectDB()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
