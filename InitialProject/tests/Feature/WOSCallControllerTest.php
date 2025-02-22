<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Paper;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WOSCallControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_imports_papers_successfully()
    {
        // Fake response สำหรับ API ของ Clarivate
        Http::fake([
            'https://api.clarivate.com/apis/wos-starter/v1/documents*' => Http::response([
                'hits' => [
                    [
                        'title'        => 'Test Paper',
                        'types'        => ['Journal Article'],
                        'sourceTypes'  => ['Article'],
                        'source'       => [
                            'sourceTitle' => 'Test Journal',
                            'publishYear' => 2020,
                            'volume'      => '10',
                            'issue'       => '2',
                            'pages'       => [
                                'range' => '100-110'
                            ]
                        ],
                        'links'        => [
                            'record' => 'https://example.com/record'
                        ],
                        'identifiers'  => [
                            'doi' => '10.1234/testdoi'
                        ],
                        'citations'    => [
                            ['count' => 5]
                        ],
                        'keywords'     => [
                            'authorKeywords' => ['keyword1', 'keyword2']
                        ],
                        'names'        => [
                            'authors' => [
                                ['displayName' => 'Wongthanavasu, Sartra'],
                                ['displayName' => 'Doe, John']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        // สร้าง User (Teacher) สำหรับทดสอบ
        $user = User::factory()->create([
            'fname_en' => 'Sartra',
            'lname_en'  => 'Wongthanavasu',
        ]);

        // เข้ารหัส id ของ user เพื่อใช้เป็น parameter
        $encryptedId = Crypt::encrypt($user->id);

        // เรียก route ที่ map กับ controller (เช่น /wos-call/create/{id})
        $response = $this->get('/wos-call/create/' . $encryptedId);

        // ตรวจสอบว่ามีการ redirect กลับไปยังหน้าก่อนหน้าและมี flash message success
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Web of Science records imported successfully.');

        // ตรวจสอบว่ามีการสร้าง Paper ในฐานข้อมูล
        $this->assertDatabaseHas('papers', [
            'paper_name' => 'Test Paper',
            'paper_type' => 'Journal Article',
        ]);

        // หากต้องการตรวจสอบ relationship เช่น teacher หรือ author สามารถเพิ่ม assert เพิ่มเติมได้
    }

    public function test_create_redirects_with_error_when_user_not_found()
    {
        // สร้าง encrypted id ที่ไม่ตรงกับ User ใดๆ (เช่น 9999)
        $encryptedId = Crypt::encrypt(9999);

        // เรียก route ที่ map กับ controller
        $response = $this->get('/wos-call/create/' . $encryptedId);

        // ตรวจสอบว่ามีการ redirect และมี flash message error
        $response->assertRedirect();
        $response->assertSessionHas('error', 'User not found');
    }
}
