<?php

use HackbartPR\Entity\Category;
use PHPUnit\Framework\TestCase;
use HackbartPR\Seeds\CategorySeed;
use HackbartPR\Tests\Traits\Request;

final class CategoryTest extends TestCase
{
    use Request;

    //INSERT METHODS
    public function testShouldInsertCategory(): array
    {
        $category = CategorySeed::create();

        $request = $this->createRequest('POST', '/categorias', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);

        return $body['contents'];
    }

    public function testShouldNotInsertCategoryWithoutTitle(): void
    {
        $category = new Category(null, '', '#ffffff');
        
        $request = $this->createRequest('POST', '/categorias', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    public function testShouldNotInsertCategoryWithoutColor(): void
    {
        $category = new Category(null, 'Branco', '');
        
        $request = $this->createRequest('POST', '/categorias', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    public function testShouldNotInsertCategoryWithWrongColor(): void
    {
        $category = new Category(null, 'Branco', 'ffffff');
        
        $request = $this->createRequest('POST', '/categorias', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotInsertVideoWithURLDuplicated(array $seed): void
    {
        $category = new Category(null, 'titulo de Teste', $seed['color']);
        $request = $this->createRequest('POST', '/categorias', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Color already exists.']));
    } 
}