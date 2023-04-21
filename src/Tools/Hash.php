<?php

namespace HackbartPR\Tools;

final class Hash
{
    public function __construct(
        private \PDO $pdo
    ){}

    public static function passwordHash(string $password): string
    {
        return \password_hash($password, constant($_ENV['CURRENT_HASH']));   
    }

    public static function passwordVerify(string $password, string $hash): bool
    {  
        $authorized = \password_verify($password, $hash ?? '');

        if (password_needs_rehash($hash, $_ENV['CURRENT_HASH'])) {
            $newHash = \password_hash($hash, $_ENV['CURRENT_HASH']);             
            
            $stmt = self::$pdo->prepare("UPDATE users SET password = ? WHERE password = ?");
            $stmt->bindValue(1, $newHash);
            $stmt->bindValue(2, $hash);
            $stmt->execute();
        }
        
        return $authorized;
    }
}