<?php

use PHPUnit\Framework\TestCase;
use HackbartPR\Seeds\VideoSeed;
use HackbartPR\Tests\Traits\Mock;
use HackbartPR\Tests\Traits\Request;

final class VideoTest extends TestCase
{
    use Request, Mock;

    public function testShouldListOfVideos(): void
    {
        $request = $this->createRequest('GET', '/videos');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }

    public function testShouldDeleteVideo(): void
    {
        $seed = VideoSeed::create();
        $repository = $this->getVideoRepository();
        $repository->save($seed);
        
        $video = $repository->showByUrl($seed->url);

        $request = $this->createRequest('DELETE', '/videos/' . $video['id']);
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