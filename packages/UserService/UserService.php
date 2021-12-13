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

    public function addUser(User $user): int
    {
        $login = $user->getLogin();
        $password = $user->getPassword();
        $this->connection->query("INSERT INTO user (login, password) VALUES ('$login', '$password');");
        return mysqli_insert_id($this->connection);
    }

    public function addUserOnlyLogin(User $user): int
    {
        $login = $user->getLogin();
        $this->connection->query("INSERT INTO user (login) VALUES ('$login');");
        return mysqli_insert_id($this->connection);

    }

    public function changePassword(User $user, string $password): bool
    {
        $id = $user->getId();
        return $this->connection->query("UPDATE user SET `password` = '$password' WHERE id=$id");
    }
}