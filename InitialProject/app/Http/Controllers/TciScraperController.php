<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HeadlessChromium\BrowserFactory;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TciScraperController extends Controller
{
    protected $browser;

    public function __construct()
    {
        // สร้าง instance ของ BrowserFactory เพื่อเปิด Headless Chrome
        $browserFactory = new BrowserFactory();
        $this->browser = $browserFactory->createBrowser([
            'binary' => 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', // เส้นทางไปที่ executable ของ Chrome
            'args' => ['--headless', '--disable-gpu', '--no-sandbox'], // เพิ่ม arguments สำหรับ headless
            'debugLogger' => 'php://stdout',
        ]);

        // ตรวจสอบว่าเบราว์เซอร์สามารถเริ่มต้นได้หรือไม่
        if (!$this->browser) {
            Log::error("ไม่สามารถเชื่อมต่อกับ Headless Chrome ได้");
            throw new \Exception('ไม่สามารถเชื่อมต่อกับ Headless Chrome ได้');
        }
    }

    public function searchAuthorsFromDatabase()
    {
        Log::debug('เริ่มต้นการทำงานในฟังก์ชัน searchAuthorsFromDatabase (Headless Chrome)');

        // ดึงข้อมูลผู้แต่งจากฐานข้อมูล (ในตัวอย่างนี้สมมุติว่ามีในตาราง users)
        $users = DB::table('users')->select('fname_en', 'lname_en')->get();
        Log::debug('จำนวนผู้แต่งที่ดึงมา: ' . $users->count());

        $articlesData = [];

        foreach ($users as $user) {
            $authorName = trim($user->fname_en . ' ' . $user->lname_en);
            Log::debug("กำลังประมวลผลผู้แต่ง: {$authorName}");

            // เปิดหน้า Advance Search ด้วย Headless Chrome
            $page = $this->browser->createPage();
            $page->navigate('https://search.tci-thailand.org/advance_search.html')->waitForNavigation(30000); // รอ 30 วินาที

            // รอให้มี input กับ select ปรากฏใน DOM
            $page->evaluate('document.querySelector("input[name=\'keyword[]\']") !== null');
            $page->evaluate('document.querySelector("select[name=\'criteria[]\']") !== null');

            // กำหนดค่าให้กับ input (ช่องที่ใช้พิมพ์ชื่อ) และเลือก dropdown เป็น "author"
            $page->evaluate(sprintf(
                'document.querySelector("input[name=\'keyword[]\']").value = "%s";',
                addslashes($authorName)
            ));
            $page->evaluate('document.querySelector("select[name=\'criteria[]\']").value = "author";');

            // กดปุ่ม Search (ปุ่มที่มี id="searchBtn")
            $page->evaluate('document.getElementById("searchBtn").click();');

            // รอการนำทางไปยังหน้าผลลัพธ์
            $page->waitForNavigation(30000); // รอ 30 วินาที

            // ดึง HTML ของหน้าผลลัพธ์หลังจากการ render เสร็จ
            $html = $page->getHtml();
            Log::debug("ความยาวของ HTML ผลลัพธ์สำหรับ {$authorName}: " . strlen($html));

            // ใช้ DomCrawler วิเคราะห์ HTML ที่ได้
            $crawler = new Crawler($html);
            $articles = [];
            $resultItems = $crawler->filter('.search-result-item');
            $resultCount = $resultItems->count();
            Log::debug("จำนวนผลการค้นหาสำหรับผู้แต่ง {$authorName}: {$resultCount}");

            if ($resultCount === 0) {
                Log::warning("ไม่พบผลลัพธ์การค้นหาสำหรับผู้แต่ง: {$authorName}");
                continue; // ข้ามไปยังผู้แต่งถัดไป
            }

            // วนลูปดึงข้อมูลจากแต่ละผลการค้นหา
            $resultItems->each(function (Crawler $node) use (&$articles) {
                try {
                    $link  = $node->filter('a')->attr('href');
                    $title = $node->filter('.title')->text();
                    $articles[] = [
                        'title' => $title,
                        'link'  => $link,
                    ];
                    Log::debug("พบ Article: {$title} , Link: {$link}");
                } catch (\Exception $e) {
                    Log::error("เกิดข้อผิดพลาดในการดึงข้อมูล Article: " . $e->getMessage());
                }
            });

            $articlesData[$authorName] = $articles;
            $page->close();
        }

        $this->browser->close();
        Storage::put('authors_articles.json', json_encode($articlesData, JSON_PRETTY_PRINT));
        Log::debug('เสร็จสิ้นการประมวลผลผู้แต่งทั้งหมดและบันทึกผลลัพธ์ลงใน authors_articles.json');

        return response()->json($articlesData);
    }
}
