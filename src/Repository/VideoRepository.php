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
        $stmt = $this->pdo->prepare("INSERT INTO videos (title, description, url, category_id) VALUES (:title, :desc, :url, :ctg);");
        $stmt->bindValue(':title', $video->title, FILTER_DEFAULT);
        $stmt->bindValue(":desc", $video->description, FILTER_DEFAULT);
        $stmt->bindValue(":url", $video->url, FILTER_VALIDATE_URL);
        $stmt->bindValue(":ctg", $video->category->id(), FILTER_VALIDATE_INT);

        return $stmt->execute();        
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM videos");
        return $stmt->fetchAll();
    }

    public function show(int $id): array|bool
    {
        $query = "SELECT *, CTG.title AS 'ctg_title', CTG.color FROM videos 
        INNER JOIN categories CTG ON videos.category_id = CTG.id
        WHERE id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetch();        
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }

    public function showByUrl(string $url): array|bool
    {
        $query = "SELECT *, CTG.title AS 'ctg_title', CTG.color FROM videos 
        INNER JOIN categories CTG ON videos.category_id = CTG.id
        WHERE url = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $url);
        $stmt->execute();

        $response = $stmt->fetch();

        if (!$response) {
            return $response;
        }

        return $this->hydrateVideoArray($response); 
    }

    private function hydrateVideoArray(array $video): array
    {
        return [
            'id' => $video['id'],
            'title' => $video['title'],
            'description' => $video['description'],
            'url' => $video['url'],
            'category' => [
                'id' => $video['category_id'],
                'title' => $video['ctg_title'],
                'color' => $video['color']
            ]
        ];
    }

}