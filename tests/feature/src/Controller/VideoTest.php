<?php

use HackbartPR\Entity\Video;
use PHPUnit\Framework\TestCase;
use HackbartPR\Seeds\VideoSeed;
use HackbartPR\Tests\Traits\Request;

final class VideoTest extends TestCase
{
    use Request;

    //INSERT METHODS
    public function testShouldInsertVideo(): array
    {
        $video = VideoSeed::create();

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);

        return $body['contents'];
    }

    public function testShouldNotInsertVideoWithoutDescription(): void
    {
        $video = new Video(null, 'titulo de Teste', '', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithoutTitle(): void
    {
        $video = new Video(null, '', 'Descrição de Teste', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithoutURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', '');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithWrongURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', 'google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotInsertVideoWithURLDuplicated(array $seed): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de Teste', $seed['url']);
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'URL already exists.']));
    } 



    //UPDATE METHODS
    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWithoutTitlePatchMethod(array $seed): void
    {
        $video = new Video($seed['id'], '', 'Descricao de Teste', $seed['url']);
        $request = $this->createRequest('PATCH', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWithoutTitle(array $seed): void
    {
        $video = new Video($seed['id'], '', $seed['description'], $seed['url']);
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWithoutDescription(array $seed): void
    {
        $video = new Video($seed['id'], $seed['title'], '', $seed['url']);
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWithoutURL(array $seed): void
    {
        $video = new Video($seed['id'], $seed['title'], $seed['description'], '');
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWrongURLFormat(array $seed): void
    {
        $video = new Video($seed['id'], $seed['title'], $seed['description'], 'google.com');
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldNotUpdateVideoWithURLDuplicated(array $seed): void
    {
        $newVideo = $this->testShouldInsertVideo();
        $video = new Video($newVideo['id'], 'Titulo de Teste', 'Descricao de Teste', $seed['url']);
        
        $request = $this->createRequest('PUT', '/videos/' . $newVideo['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'URL already exists.']));
    }

    public function testShouldNotUpdateVideoWhichNotExist(): void
    {
        $video = new Video(0, 'Titulo de Teste', 'Descricao de Teste', 'http://www.google.com');
        $request = $this->createRequest('PUT', '/videos/0', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video not found.']));
    }

    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldUpdateVideo(array $seed): void
    {
        $video = new Video($seed['id'], 'Titulo de Teste Updated', $seed['description'], $seed['url']);
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertEquals('Titulo de Teste Updated', $body['contents']['title']);
    }
 


    //SHOW METHODS
    public function testShouldShowVideoWithFalseContents(): void
    {
        $request = $this->createRequest('GET', '/videos/0');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertEquals(false, $body['contents']);
    }
    
    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldShowVideo(array $seed): void
    {
        $request = $this->createRequest('GET', '/videos/' . $seed['id']);
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }

    public function testShouldListOfVideos(): void
    {
        $request = $this->createRequest('GET', '/videos');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }



    //DELETE METHODS
    /**
     * @depends testShouldInsertVideo
     */
    public function testShouldDeleteVideo(array $seed): void
    {                
        $request = $this->createRequest('DELETE', '/videos/' . $seed['id']);
        $response = $this->sendRequest($request);

        $this->assertEquals(200, $response->getStatusCode());        
    }

    public function testShouldNotDeleteVideo(): void
    {
        $request = $this->createRequest('DELETE', '/videos/0');
        $response = $this->sendRequest($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video not found.']));
    }
}