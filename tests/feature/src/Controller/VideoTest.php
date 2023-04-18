<?php

use HackbartPR\Entity\Category;
use HackbartPR\Entity\Video;
use HackbartPR\Seeds\CategorySeed;
use PHPUnit\Framework\TestCase;
use HackbartPR\Seeds\VideoSeed;
use HackbartPR\Tests\Traits\Request;

final class VideoTest extends TestCase
{
    use Request;

    //INSERT METHODS
    public function testShouldNotInsertVideoWithNonexistCategory(): void
    {   
        $video = VideoSeed::create();
        $category = CategorySeed::create();
        
        $payload =  [            
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => 0,
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $test = json_encode($payload);

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Category not found.'], $body);
    }

    public function testShouldNotInsertVideoWithoutCategorysTitle(): void
    {   
        $video = VideoSeed::create();
        $category = CategorySeed::create();
        
        $payload =  [            
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => 0,
                'title' => '',
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Video format is not allowed.'], $body);
    }

    public function testShouldNotInsertVideoWithoutCategorysColor(): void
    {   
        $video = VideoSeed::create();
        $category = CategorySeed::create();
        
        $payload =  [            
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => 0,
                'title' => $category->title,
                'color' => ''
            ]
        ];

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Video format is not allowed.'], $body);
    }

    public function testShouldNotInsertVideoWithoutIdCategoryEvenCategoryHasTitleAndColor(): void
    {   
        $video = VideoSeed::create();
        $category = CategorySeed::create();
        
        $payload =  [            
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => '',
                'title' => $category->title,
                'color' => $category->color,
            ]
        ];

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Category not found.'], $body);
    }    

    public function testShouldInsertVideoWithoutCategory(): array
    {
        $video = VideoSeed::create();

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertArrayHasKey('category', $body['contents']);        
        $this->assertEquals(1, $body['contents']['category']['id']);        

        return $body['contents'];
    }

    public function testShouldInsertVideoWithCategory(): void
    {
        $video = VideoSeed::create();
        $payload =  [            
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => 1,
                'title' => 'Livre',
                'color' => '#32CD32'
            ]
        ];

        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertArrayHasKey('category', $body['contents']); 
        $this->assertArrayHasKey('id', $body['contents']['category']);
        $this->assertequals(1, $body['contents']['category']['id']);        
    }
    
    public function testShouldNotInsertVideoWithoutDescription(): void
    {
        $video = new Video(null, 'titulo de Teste', '', 'www.google.com', null);
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithoutTitle(): void
    {
        $video = new Video(null, '', 'Descrição de Teste', 'www.google.com', null);
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithoutURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', '', null);
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    public function testShouldNotInsertVideoWithWrongURL(): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de teste', 'google.com', null);
        
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);

        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotInsertVideoWithURLDuplicated(array $seed): void
    {
        $video = new Video(null, 'titulo de Teste', 'Descricao de Teste', $seed['url'], null);
        $request = $this->createRequest('POST', '/videos', ['Content-Type' => 'application/json'], json_encode($video));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'URL already exists.']));
    } 



    //UPDATE METHODS
    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutTitlePatchMethod(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], '', 'Descricao de Teste', $seed['url'], $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PATCH', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutTitle(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], '', $seed['description'], $seed['url'], $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutDescription(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], $seed['title'], '', $seed['url'], $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];
        
        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutURL(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], $seed['title'], $seed['description'], '', $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWrongURLFormat(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], $seed['title'], $seed['description'], 'google.com', $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video format is not allowed.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithURLDuplicated(array $seed): void
    {   
        $newVideo = $this->testShouldInsertVideoWithoutCategory();
        $category = new Category($newVideo['category']['id'], $newVideo['category']['title'], $newVideo['category']['color']);
        $video = new Video($newVideo['id'], 'Titulo de Teste', 'Descricao de Teste', $seed['url'], $category);

        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $payload['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);                                                                       
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'URL already exists.']));
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWhichNotExist(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video(0, 'Titulo de Teste', 'Descricao de Teste', 'http://www.google.com', $category);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/0', ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson(json_encode(['error' => 'Video not found.']));
    }
        
    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutCategorysTitle(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], 'Titulo de Teste Updated', $seed['description'], $seed['url'], $category);        
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => '',
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $payload['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Video format is not allowed.'], $body); 
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldNotUpdateVideoWithoutCategorysColor(array $seed): void
    {
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], 'Titulo de Teste Updated', $seed['description'], $seed['url'], $category);        
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => ''
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $payload['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Video format is not allowed.'], $body); 
    }

    /**
     * @depends testShouldInsertVideoWithoutCategory
     */
    public function testShouldUpdateVideo(array $seed): void
    {   
        $category = new Category($seed['category']['id'], $seed['category']['title'], $seed['category']['color']);
        $video = new Video($seed['id'], 'Titulo de Teste Updated', $seed['description'], $seed['url'], $category);        
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url,
            'category' => [
                'id' => $category->id(),
                'title' => $category->title,
                'color' => $category->color
            ]
        ];

        $request = $this->createRequest('PUT', '/videos/' . $seed['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertEquals('Titulo de Teste Updated', $body['contents']['title']);
    }

    public function testShouldUpdateVideoWithoutCategory(): void
    {
        $newVideo = $this->testShouldInsertVideoWithoutCategory();        
        $video = new Video($newVideo['id'], 'Alterando o Titulo', $newVideo['description'], $newVideo['url'], null);
        $payload =  [     
            'id' => $video->id(),
            'title' => $video->title,
            'description' => $video->description,
            'url' => $video->url
        ];

        $request = $this->createRequest('PUT', '/videos/' . $payload['id'], ['Content-Type' => 'application/json'], json_encode($payload));
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertArrayHasKey('category', $body['contents']);        
        $this->assertEquals(1, $body['contents']['category']['id']); 
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
     * @depends testShouldInsertVideoWithoutCategory
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

    public function testShouldShowAListOfVideosFromSpecificCategory(): void
    {
        $request = $this->createRequest('GET', '/categorias/1/videos');
        $response = $this->sendRequest($request);
        $body = json_decode($response->getBody()->getContents(), true);
        
        #Falta criar este test
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('contents', $body);
        $this->assertGreaterThan(1, count($body['contents']));
        $this->assertArrayHasKey('id', $body['contents'][0]);
        $this->assertArrayHasKey('title', $body['contents'][0]);
        $this->assertArrayHasKey('description', $body['contents'][0]);
        $this->assertArrayHasKey('url', $body['contents'][0]);
        $this->assertArrayHasKey('category', $body['contents'][0]);        
    }



    //DELETE METHODS
    /**
     * @depends testShouldInsertVideoWithoutCategory
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