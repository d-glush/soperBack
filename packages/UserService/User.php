<?php

namespace UserService;

class User
{
    private ?int $id;
    private string $login;
    private ?string $password;

    public function __construct(array $dataArray)
    {
        if (isset($dataArray['id'])) {
            $this->id = (int)$dataArray['id'];
        }
        if (isset($dataArray['password'])) {
            $this->password = $dataArray['password'];
        }
        $this->login = $dataArray['login'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}