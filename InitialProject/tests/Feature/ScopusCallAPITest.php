<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Paper;
use App\Models\Source_data;
use App\Models\Author;
use Carbon\Carbon;

class ScopusCallAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * ทดสอบกรณีที่ส่งค่า ID ที่ไม่สามารถถอดรหัสได้
     */
    public function testInvalidPayload()
    {
        $response = $this->get('/scopus-call/invalid-encrypted-id');
        $response->assertStatus(400);
    }

    /**
     * ทดสอบกรณีที่ไม่พบ User
     */
    public function testUserNotFound()
    {
        $encryptedId = Crypt::encrypt(999);
        $response = $this->get("/scopus-call/{$encryptedId}");

        $response->assertStatus(404);
    }

    /**
     * ทดสอบกรณีที่ API ทำงานสำเร็จ
     */
    public function testSuccessfulProcessing()
    {
        // สร้าง user
        $user = User::factory()->create([
            'fname' => 'John',
            'lname' => 'Doe'
        ]);

        // สร้าง source data
        Source_data::create([
            'id' => 1,
            'source_name' => 'Scopus'
        ]);

        // Mock HTTP responses
        Http::fake([
            'api.elsevier.com/content/search/scopus*' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Test Paper',
                            'prism:aggregationType' => 'Journal',
                            'subtypeDescription' => 'Article',
                            'prism:publicationName' => 'Test Journal',
                            'link' => [
                                ['@href' => 'link1'],
                                ['@href' => 'link2'],
                                ['@href' => 'http://test.com']
                            ],
                            'prism:coverDate' => '2023-01-01',
                            'prism:volume' => '1',
                            'prism:issueIdentifier' => '1',
                            'citedby-count' => '10',
                            'prism:pageRange' => '1-10',
                            'prism:doi' => '10.1000/test',
                            'dc:identifier' => 'SCOPUS_ID:12345'
                        ]
                    ],
                    'link' => []
                ]
            ]),
            'api.elsevier.com/content/abstract/scopus_id/*' => Http::response([
                'abstracts-retrieval-response' => [
                    'authors' => [
                        'author' => [
                            [
                                'ce:given-name' => 'John',
                                'ce:surname' => 'Doe'
                            ]
                        ]
                    ],
                    'item' => [
                        'bibrecord' => [
                            'head' => [
                                'abstracts' => 'Test abstract',
                                'citation-info' => [
                                    'author-keywords' => [
                                        'author-keyword' => ['test']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
        ]);

        // เรียก API
        $encryptedId = Crypt::encrypt($user->id);
        $response = $this->get("/scopus-call/{$encryptedId}");

        // ตรวจสอบการ redirect
        $response->assertRedirect();

        // ตรวจสอบข้อมูลในฐานข้อมูล
        $this->assertDatabaseHas('papers', [
            'paper_name' => 'Test Paper',
            'paper_type' => 'Journal',
            'paper_subtype' => 'Article',
            'paper_sourcetitle' => 'Test Journal',
            'paper_yearpub' => '2023',
            'paper_citation' => '10'
        ]);

        // ตรวจสอบความสัมพันธ์
        $paper = Paper::where('paper_name', 'Test Paper')->first();
        $this->assertTrue($paper->teacher()->where('user_id', $user->id)->exists());
        $this->assertTrue($paper->source()->where('source_data_id', 1)->exists());
    }

    /**
     * ทดสอบกรณี API ภายนอกมีปัญหา
     */
    public function testAPIFailure()
    {
        $user = User::factory()->create();

        Http::fake([
            'api.elsevier.com/content/search/scopus*' => Http::response(null, 500),
        ]);

        $encryptedId = Crypt::encrypt($user->id);
        $response = $this->get("/scopus-call/{$encryptedId}");

        $response->assertStatus(500);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}