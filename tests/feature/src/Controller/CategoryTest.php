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

    /* public function testShouldNotInsertVideoWithoutDescription(): void
    {
        $video = new Video(null, 'titulo de Teste', '', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    } */

    /* public function testShouldNotInsertVideoWithoutTitle(): void
    {
        $video = new Video(null, '', 'Descrição de Teste', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    } */

    /* public function testShouldNotInsertVideoWithoutURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', '');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    } */

    /* public function testShouldNotInsertVideoWithWrongURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', 'google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    } */

    /**
     * @depends testShouldInsertVideo
     */
    /* public function testShouldNotInsertVideoWithURLDuplicated(array $seed): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de Teste', $seed['url']);
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'URL already exists.']));
    } */ 
}