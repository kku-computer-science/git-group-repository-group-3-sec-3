<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\User;
use App\Models\Paper;
use App\Models\Source_data;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WOSCallController extends Controller
{
    /**
     * ดึงข้อมูลจาก Web of Science API (endpoint /documents)
     * โดยใช้ข้อมูลของ teacher ที่ login อยู่ (ผ่าน encrypted id)
     * Query format: AU=(Lastname, Firstname)
     * ตัวอย่าง URL ทดสอบ:
     * https://api.clarivate.com/apis/wos-starter/v1/documents?q=AU=(Wongthanavasu, Sartra)&limit=10
     *
     * @param string $id (encrypted id)
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // ถอดรหัส id ที่ส่งเข้ามา (encrypted id)
        $id = Crypt::decrypt($id);
        $userId = $id;
        $user = User::find($userId);
        if (!$user) {
            Log::error("WOSCallController: User not found for ID: {$userId}");
            return redirect()->back()->with('error', 'User not found');
        }

        // ใช้ชื่อภาษาอังกฤษของ teacher (ใช้ชื่อเต็ม)
        $fname = $user->fname_en;
        $lname = $user->lname_en;

        // สร้าง query สำหรับ Web of Science (รูปแบบ AU=(Lastname, Firstname))
        // ให้เว้นวรรคหลัง comma ตามที่ API ยอมรับ
        $query = "AU=(" . $lname . ", " . $fname . ")";
        Log::info("WOSCallController: Query = " . $query);

        // เรียก Web of Science API โดยใช้ endpoint /documents พร้อม parameter 'q' และ 'limit'
        $response = Http::withHeaders([
            'X-ApiKey' => '4f5a44cc228a61c04dd767924a9ff305c81de6ad', // ตรวจสอบให้แน่ใจว่าใช้ API Key ที่ถูกต้อง
            'Accept'   => 'application/json'
        ])->get('https://api.clarivate.com/apis/wos-starter/v1/documents', [
            'q'     => $query,
            'limit' => 50
        ])->json();

        Log::info("WOSCallController: API response = " . print_r($response, true));

        // ดึง records จาก key "hits"
        $records = [];
        if (isset($response['hits'])) {
            $records = $response['hits'];
        }
        Log::info("WOSCallController: Number of records received: " . count($records));

        $importedCount = 0;
        foreach ($records as $item) {
            if (isset($item['error'])) {
                Log::warning("WOSCallController: Skipping record due to error: " . print_r($item['error'], true));
                continue;
            }
            // ใช้ paper_name เป็น key หลักในการตรวจสอบว่ามี record นี้อยู่แล้วหรือไม่
            $existingPaper = Paper::where('paper_name', $item['title'])->first();
            if (!$existingPaper) {
                // สร้าง record ใหม่
                $paper = new Paper;
                $paper->paper_name = $item['title'] ?? null;
                $paper->paper_type = isset($item['types'][0]) ? $item['types'][0] : null;
                $paper->paper_subtype = isset($item['sourceTypes'][0]) ? $item['sourceTypes'][0] : null;
                $paper->paper_sourcetitle = $item['source']['sourceTitle'] ?? null;
                $paper->paper_yearpub = isset($item['source']['publishYear']) ? $item['source']['publishYear'] : null;
                $paper->paper_volume = $item['source']['volume'] ?? null;
                $paper->paper_issue = $item['source']['issue'] ?? null;
                $paper->paper_page = isset($item['source']['pages']['range']) ? $item['source']['pages']['range'] : null;
                $paper->paper_url = $item['links']['record'] ?? null;
                $paper->paper_doi = $item['identifiers']['doi'] ?? null;
                $paper->paper_citation = isset($item['citations'][0]['count']) ? $item['citations'][0]['count'] : null;
                // ในตัวอย่าง JSON ไม่มี abstract
                $paper->abstract = null;
                $paper->keyword = isset($item['keywords']['authorKeywords']) ? json_encode($item['keywords']['authorKeywords']) : null;
                
                // กำหนด source_data_id = 2
                // $paper->source_data_id = 2;
                
                $paper->save();
                $importedCount++;

                // ผูก Paper กับ Source_data ผ่าน relationship (ถ้าใช้ pivot table)
                // หากคุณเก็บ source_data_id ในตาราง papers แล้วอาจไม่ต้องใช้ relationship นี้อีก แต่ถ้ายังต้องการให้ผูกใน pivot table
                $source = Source_data::findOrFail(2); // เปลี่ยนเป็น 2 ตามที่ต้องการ
                $paper->source()->sync($source);

                // ประมวลผล Authors จาก names.authors
                $all_au = isset($item['names']['authors']) ? $item['names']['authors'] : [];
                $x = 1;
                $length = count($all_au);
                foreach ($all_au as $i) {
                    $displayName = $i['displayName'] ?? '';
                    $parts = explode(',', $displayName);
                    if (count($parts) < 2) {
                        continue;
                    }
                    $lastNameAuthor = trim($parts[0]);
                    $firstNameAuthor = trim($parts[1]);
                    if (empty($firstNameAuthor) || empty($lastNameAuthor)) {
                        continue;
                    }
                    // ตรวจสอบในตาราง User
                    $existingUser = User::where([
                        ['fname_en', '=', $firstNameAuthor],
                        ['lname_en', '=', $lastNameAuthor]
                    ])->first();
                    if (!$existingUser) {
                        // ตรวจสอบในตาราง Author
                        $existingAuthor = Author::where([
                            ['author_fname', '=', $firstNameAuthor],
                            ['author_lname', '=', $lastNameAuthor]
                        ])->first();
                        if (!$existingAuthor) {
                            $author = new Author;
                            $author->author_fname = $firstNameAuthor;
                            $author->author_lname = $lastNameAuthor;
                            $author->save();
                            $authorId = $author->id;
                        } else {
                            $authorId = $existingAuthor->id;
                        }
                        $authorType = ($x === 1) ? 1 : (($x === $length) ? 3 : 2);
                        $paper->author()->attach($authorId, ['author_type' => $authorType]);
                    } else {
                        $authorType = ($x === 1) ? 1 : (($x === $length) ? 3 : 2);
                        $paper->teacher()->attach($existingUser->id, ['author_type' => $authorType]);
                    }
                    $x++;
                }
            } else {
                // หาก paper มีอยู่แล้ว ให้ผูก Teacher กับ paper หากยังไม่ผูก
                $paper = $existingPaper;
                $hasTask = $user->paper()->where('paper_id', $paper->id)->exists();
                if (!$hasTask) {
                    $paper->teacher()->attach($user->id);
                }
            }
        }
        
        Log::info("WOSCallController: Imported paper count: " . $importedCount);
        return redirect()->back()->with('success', 'Web of Science records imported successfully.');
    }
}
