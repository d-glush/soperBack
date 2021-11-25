<?php

namespace UserService;

use mysqli;

class UserService {
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByLogin(string $login): User|bool
    {
        $result = $this->connection->query("SELECT * FROM user WHERE login='$login';");
        if ($result && $result->num_rows !== 0) {
            return new User($result->fetch_assoc());
        }
        return false;
    }

    public function addUser(User $user): bool
    {
        $login = $user->getLogin();
        $password = $user->getPassword();
        return $this->connection->query("INSERT INTO user (login, password) VALUES ('$login', '$password');");
    }

    public function addUserOnlyLogin(User $user): bool
    {
        $login = $user->getLogin();
        return $this->connection->query("INSERT INTO user (login) VALUES ('$login');");
    }
}