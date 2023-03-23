<?php

namespace HackbartPR\Repository;

use HackbartPR\Entity\Video;

class VideoRepository
{
    public function __construct(
        private \PDO $pdo
    ){}

    public function save(Video $video): bool
    {
        if (!is_null($video->id())) {
            return $this->update($video);
        }

        return $this->add($video);
    }

    private function update(Video $video): bool
    {
        $stmt = $this->pdo->prepare("UPDATE videos SET title = :title, description = :desc, url = :url WHERE id = :id");
        $stmt->bindValue(':id', $video->id(), FILTER_VALIDATE_INT);
        $stmt->bindValue(':title', $video->title, FILTER_DEFAULT);
        $stmt->bindValue(":desc", $video->description, FILTER_DEFAULT);
        $stmt->bindValue(":url", $video->url, FILTER_VALIDATE_URL);
        
        return $stmt->execute();;
    }

    private function add(Video $video): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO videos (title, description, url) VALUES (:title, :desc, :url);");
        $stmt->bindValue(':title', $video->title, FILTER_DEFAULT);
        $stmt->bindValue(":desc", $video->description, FILTER_DEFAULT);
        $stmt->bindValue(":url", $video->url, FILTER_VALIDATE_URL);

        return $stmt->execute();        
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM videos");
        return $stmt->fetchAll();
    }

    public function show(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetch();        
    }

}