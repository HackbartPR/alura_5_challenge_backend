<?php

namespace HackbartPR\Repository;

class UserRepository
{
    public function __construct(
        private \PDO $pdo
    ){}

    public function searchByEmail(string $email): array|bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bindValue(1, $email, FILTER_VALIDATE_EMAIL);        
        $stmt->execute();

        return $stmt->fetch();
    }

    public function isEmailRegistered(string $email): array|bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(id) FROM users WHERE email = ?");
        $stmt->bindValue(1, $email, FILTER_VALIDATE_EMAIL);        
        $stmt->execute();

        return $stmt->fetch();
    }
}