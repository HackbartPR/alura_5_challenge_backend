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



    //UPDATE METHODS
    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotUpdateCategoryWithoutTitlePatchMethod(array $seed): void
    {
        $category = new Category($seed['id'], '', $seed['color']);
        $request = $this->createRequest('PATCH', '/categorias/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotUpdateCategoryWithoutTitle(array $seed): void
    {
        $category = new Category($seed['id'], '', $seed['color']);
        $request = $this->createRequest('PUT', '/categorias/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }


    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotUpdateCategoryWithoutColor(array $seed): void
    {
        $category = new Category($seed['id'], $seed['title'], '');
        $request = $this->createRequest('PUT', '/categorias/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotUpdateCategoryWrongcolorFormat(array $seed): void
    {
        $category = new Category($seed['id'], $seed['title'], 'ffffff');
        $request = $this->createRequest('PUT', '/categorias/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category format is not allowed.']));
    }

    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldNotUpdateCategoryWithColorDuplicated(array $seed): void
    {
        $newCategory = $this->testShouldInsertCategory();
        $category = new Category($newCategory['id'], 'Titulo de Teste', $seed['color']);
        
        $request = $this->createRequest('PUT', '/categorias/' . $newCategory['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'color already exists.']));
    }

    public function testShouldNotUpdateCategoryWhichNotExist(): void
    {
        $category = new Category(0, 'Titulo de Teste', '#f1f3f1');
        $request = $this->createRequest('PUT', '/categorias/0', ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Category not found.']));
    }

    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldUpdateCategory(array $seed): void
    {
        $category = new Category($seed['id'], 'Titulo de Teste Updated', $seed['color']);
        $request = $this->createRequest('PUT', '/categorias/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($category));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertEquals('Titulo de Teste Updated', $body['contents']['title']);
    }



    //SHOW METHODS
    public function testShouldListOfCategories(): void
    {
        $request = $this->createRequest('GET', '/categorias');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }

    public function testShouldShowCategoryWithFalseContents(): void
    {
        $request = $this->createRequest('GET', '/categorias/0');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertEquals(false, $body['contents']);
    }
    
    /**
     * @depends testShouldInsertCategory
     */
    public function testShouldShowCategory(array $seed): void
    {
        $request = $this->createRequest('GET', '/categorias/' . $seed['id']);
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }
}