<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LocalizationTest extends TestCase
{
    /** @test */
    public function user_can_switch_language()
{
    // รีเซ็ต Session และ Locale ก่อนเริ่มทดสอบ
    Session::flush();
    App::setLocale(config('app.fallback_locale')); 

    // ตรวจสอบว่าภาษาเริ่มต้นคือ en
    $this->assertEquals('th', App::getLocale());

    // จำลองการกดเปลี่ยนภาษาเป็นภาษาไทย
    $response = $this->get('/lang/en');

    // ตรวจสอบว่า Session มีค่า applocale เป็น th
    $this->assertEquals('en', Session::get('applocale'));

    // ตรวจสอบว่า Laravel เปลี่ยนภาษาเป็น th
    $this->assertEquals('en', App::getLocale());

    // ตรวจสอบว่า redirect กลับไปหน้าเดิม
    $response->assertStatus(302);
}

    /** @test */
    public function home_page_shows_correct_language_after_switching()
    {
        // ตั้งค่า Session เป็นภาษาไทย
        Session::put('applocale', 'en');
        App::setLocale('en');

        // เรียกหน้าแรก
        $response = $this->get('/');

        // ตรวจสอบว่ามีข้อความ "ยินดีต้อนรับ" (จาก resources/lang/th/messages.php)
        $response->assertSee(__('message.publications'));
    }

    
}