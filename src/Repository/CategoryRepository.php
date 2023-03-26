<?php

namespace HackbartPR\Repository;

use HackbartPR\Entity\Category;

class CategoryRepository
{
    public function __construct(
        private \PDO $pdo
    ){}

    public function save(Category $category): bool
    {
        if (!is_null($category->id())) {
            return $this->update($category);
        }

        return $this->add($category);
    }

    private function update(Category $category): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET title = :title, color = :color WHERE id = :id");
        $stmt->bindValue(':id', $category->id(), FILTER_VALIDATE_INT);
        $stmt->bindValue(':title', $category->title, FILTER_DEFAULT);
        $stmt->bindValue(":color", $category->color, FILTER_DEFAULT);
        
        return $stmt->execute();
    }

    private function add(Category $category): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (title, color) VALUES (:title, :color);");
        $stmt->bindValue(':title', $category->title, FILTER_DEFAULT);
        $stmt->bindValue(":color", $category->color, FILTER_DEFAULT);

        return $stmt->execute();        
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public function show(int $id): array|bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetch();        
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bindValue(1, $id);
        return $stmt->execute();
    }

    public function showByColor(string $color): array|bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE color = ?");
        $stmt->bindValue(1, $color);
        $stmt->execute();

        return $stmt->fetch(); 
    }

}