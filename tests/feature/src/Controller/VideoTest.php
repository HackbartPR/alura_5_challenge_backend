<?php


use Tests\Traits\Request;
use PHPUnit\Framework\TestCase;

final class VideoTest extends TestCase
{
    use Request;

    public function testShouldListOfVideos(): void
    {
        $request = $this->createRequest('GET', '/videos');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
    }

    
}