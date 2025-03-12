<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Paper;
use App\Models\Source_data;
use Mockery;
use SerpApi\GoogleSearch;

class ScholarCallAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * ทดสอบกรณีที่ส่งค่า ID ที่ไม่สามารถถอดรหัสได้ (API testing)
     */
    public function testInvalidPayload()
    {
        // ส่งค่า id ที่ไม่ใช่ค่าที่เข้ารหัสถูกต้อง
        $response = $this->get('/scholar-call/invalid-encrypted-id');

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid payload']);
    }

    /**
     * ทดสอบกรณีที่ไม่พบ User
     */
    public function testUserNotFound()
    {
        // สร้างค่า encrypted id สำหรับ user id ที่ไม่มีอยู่ในระบบ (เช่น 999)
        $encryptedId = Crypt::encrypt(999);
        $response = $this->get("/scholar-call/{$encryptedId}");

        $response->assertStatus(404)
                 ->assertJson(['error' => 'User not found']);
    }

    /**
     * ทดสอบกรณีที่ User ไม่มีข้อมูล author_id (หรือ authers_id)
     */
    public function testMissingAuthorId()
    {
        // สร้าง user โดยไม่กำหนด author_id หรือ authers_id
        $user = User::factory()->create([
            'author_id'  => null,
            'authers_id' => null,
        ]);

        $encryptedId = Crypt::encrypt($user->id);
        $response = $this->get("/scholar-call/{$encryptedId}");

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Author ID is missing']);
    }

    /**
     * ทดสอบกรณีที่ API ทำงานสำเร็จ (Processing articles จาก API ภายนอก)
     */
    public function testSuccessfulProcessing()
    {
        // สร้าง user ที่มี author_id
        $user = User::factory()->create([
            'author_id' => 'sample_author_id'
        ]);

        // สร้าง record สำหรับ Source_data (Google Scholar)
        $source = Source_data::create([
            'source_name' => 'Google Scholar',
            // กำหนด field อื่น ๆ ตามที่จำเป็น
        ]);

        // เตรียม Fake API Response สำหรับ GoogleSearch
        $fakeResponse = (object)[
            'articles' => [
                (object)[
                    'title'       => 'Sample Paper Title',
                    'publication' => 'Sample Publication',
                    'link'        => 'http://example.com/sample-paper',
                    'year'        => '2021',
                    'cited_by'    => (object)['value' => 10],
                    'authors'     => 'John Doe, Jane Smith'
                ]
            ]
        ];

        // Overload class SerpApi\GoogleSearch ด้วย mock instance
        $googleSearchMock = \Mockery::mock('overload:SerpApi\GoogleSearch');
        $googleSearchMock->shouldReceive('get_json')
                         ->andReturn($fakeResponse);

        // เข้ารหัส id ของ user แล้วส่ง request
        $encryptedId = Crypt::encrypt($user->id);
        $response = $this->get("/scholar-call/{$encryptedId}");

        // เนื่องจาก Controller ใช้ redirect กลับไปหน้าเดิมพร้อม session message
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Articles processed successfully!');

        // ตรวจสอบว่ามีการสร้าง Paper record ตามข้อมูลจาก API response
        $this->assertDatabaseHas('papers', [
            'paper_name'        => 'Sample Paper Title',
            'paper_sourcetitle' => 'Sample Publication',
            'paper_url'         => 'http://example.com/sample-paper',
            'paper_yearpub'     => 2021,
            'paper_citation'    => 10,
        ]);

        // ตัวอย่างตรวจสอบความสัมพันธ์กับ User (ผ่าน teacher)
        $paper = Paper::where('paper_name', 'Sample Paper Title')->first();
        $this->assertTrue($paper->teacher()->where('user_id', $user->id)->exists());
        
        // ตัวอย่างตรวจสอบความสัมพันธ์กับ Source_data (Google Scholar)
        $this->assertTrue($paper->source()->where('source_data_id', $source->id)->exists());
    }

    protected function tearDown(): void
    {
        // ปิดการใช้งาน Mockery หลังจบการทดสอบ
        \Mockery::close();
        parent::tearDown();
    }
}
