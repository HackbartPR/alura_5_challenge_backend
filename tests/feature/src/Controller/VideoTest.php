<?php

use HackbartPR\Entity\Video;
use PHPUnit\Framework\TestCase;
use HackbartPR\Seeds\VideoSeed;
use HackbartPR\Tests\Traits\Request;

final class VideoTest extends TestCase
{
    use Request;

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

    public function testShouldNotInsertVideoWithNoDescription(): void
    {
        $video = new Video(null, 'titulo de Teste', '', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithNoURL(): void
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

    public function testShouldNotInsertVideoWithNoTitle(): void
    {
        $video = new Video(null, '', 'Descrição de Teste', 'www.google.com');
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

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
        $this->assertJson(json_encode(['error' => 'User not found.']));
    }

    
}