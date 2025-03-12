<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Author;
use App\Models\Paper;
use App\Models\Source_data;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class TciScraperController extends Controller
{
    /**
     * แปลงค่าให้เป็น null หากเป็นค่าว่างหรือมีแต่ช่องว่าง
     *
     * @param mixed $value
     * @return mixed|null
     */
    private function emptyToNull($value)
    {
        return (isset($value) && trim($value) !== '') ? trim($value) : null;
    }

    /**
     * ดึงข้อมูลจาก TCI โดยใช้ชื่อของอาจารย์ที่ login อยู่ แล้วเพิ่ม paper และ authors
     *
     * @param string $id (encrypted user id)
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // ถอดรหัส id ที่ส่งเข้ามา
        $userId = Crypt::decrypt($id);
        $user = User::find($userId);
        if (!$user) {
            Log::error("TciScraperController: User not found for ID: {$userId}");
            return redirect()->back()->with('error', 'User not found');
        }

        // ดึงชื่อภาษาอังกฤษ
        $fname = $user->fname_en;
        $lname = $user->lname_en;
        // สมมุติว่าในตาราง User มีชื่อภาษาไทยด้วย
        $fname_th = isset($user->fname_th) ? $user->fname_th : '';
        $lname_th = isset($user->lname_th) ? $user->lname_th : '';

        $fullName = trim($fname . ' ' . $lname);
        Log::info("TciScraperController: Starting scraping for teacher: " . $fullName);

        // กำหนด path ของ Python script (ตรวจสอบว่าอยู่ในโฟลเดอร์ scripts)
        $pythonScript = base_path('scripts' . DIRECTORY_SEPARATOR . 'web_scraper.py');

        // สร้างคำสั่งรัน Python script พร้อมส่งพารามิเตอร์ทั้งภาษาอังกฤษและภาษาไทย
        $cmd = "python " . escapeshellarg($pythonScript) . " " 
             . escapeshellarg($fname) . " " . escapeshellarg($lname) . " " 
             . escapeshellarg($fname_th) . " " . escapeshellarg($lname_th) . " 2>&1";
        Log::info("TciScraperController: Running command: " . $cmd);

        $output = shell_exec($cmd);
        Log::info("TciScraperController: Raw Python output: " . $output);

        // ตัดเอาส่วน JSON จาก output (หาตำแหน่งแรกที่พบ [ หรือ {)
        $startPos = strpos($output, '[');
        if ($startPos === false) {
            $startPos = strpos($output, '{');
        }
        if ($startPos !== false) {
            $jsonOutput = substr($output, $startPos);
        } else {
            $jsonOutput = $output;
        }
        Log::info("TciScraperController: Extracted JSON output: " . $jsonOutput);

        // แปลงเป็น UTF-8 โดยละเว้นอักขระที่ไม่ถูกต้อง
        $jsonOutput = iconv('UTF-8', 'UTF-8//IGNORE', $jsonOutput);
        $data = json_decode($jsonOutput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("TciScraperController: JSON decode error: " . json_last_error_msg());
            return redirect()->back()->with('error', 'Invalid data format received from python script.');
        }
        if (!is_array($data)) {
            Log::error("TciScraperController: Invalid data format received from python script.");
            return redirect()->back()->with('error', 'Invalid data format received.');
        }

        $importedCount = 0;
        foreach ($data as $item) {
            // ใช้ title_th ถ้ามี ถ้าไม่มีให้ใช้ title_eng
            $title = $this->emptyToNull(isset($item['title_th']) && $item['title_th'] ? $item['title_th'] : $item['title_eng']);
            if (!$title) {
                continue;
            }

            // Duplicate check แบบเดิม: ใช้ paper_name ตรงกัน (ไม่เปลี่ยน case หรือ trim เพิ่มเติม)
            $existingPaper = Paper::where('paper_name', $title)->first();
            if (!$existingPaper) {
                $paper = new Paper;
                $paper->paper_name         = $title;
                $paper->paper_type         = $this->emptyToNull($item['research_type']);
                $paper->paper_subtype      = null;
                $paper->paper_sourcetitle  = $this->emptyToNull($item['journal']);
                $paper->paper_yearpub      = $this->emptyToNull($item['year']);
                $paper->paper_volume       = $this->emptyToNull($item['volume']);
                $paper->paper_issue        = $this->emptyToNull($item['issue']);
                $paper->paper_page         = $this->emptyToNull($item['pages']);
                $paper->paper_url          = $this->emptyToNull($item['article_url']);
                $paper->paper_doi          = $this->emptyToNull($item['doi']);
                $paper->paper_citation     = $this->emptyToNull($item['citation_count']);

                // ใช้ abstract จากภาษาไทยถ้ามี ไม่เช่นนั้นใช้ภาษาอังกฤษ
                $abstract = $this->emptyToNull(isset($item['abstract_th']) && $item['abstract_th'] ? $item['abstract_th'] : $item['abstract_eng']);
                $paper->abstract           = $abstract;

                // ใช้ keywords จากภาษาไทยถ้ามี ไม่เช่นนั้นใช้ภาษาอังกฤษ
                $keywords = $this->emptyToNull(isset($item['keywords_th']) && $item['keywords_th'] ? $item['keywords_th'] : $item['keywords_eng']);
                $paper->keyword            = $keywords;

                $paper->save();
                $importedCount++;

                // ผูก Paper กับ Source_data (สมมุติว่า source_data_id สำหรับ TCI คือ 3)
                $source = \App\Models\Source_data::find(3);
                if ($source) {
                    $paper->source()->sync([$source->id]);
                }

                // ผูก Teacher กับ Paper (สำหรับ teacher ที่ login อยู่)
                $paper->teacher()->syncWithoutDetaching([$user->id]);

                // ประมวลผล Authors จาก key "authors" ใน JSON
                if (isset($item['authors']) && is_array($item['authors']) && count($item['authors']) > 0) {
                    $allAuthorsRaw = [];
                    // Loop ผ่านทุก element ใน authors array
                    foreach ($item['authors'] as $authString) {
                        // Explode ด้วย comma
                        $names = explode(',', $authString);
                        foreach ($names as $name) {
                            $cleanName = trim($name);
                            if (!empty($cleanName)) {
                                $allAuthorsRaw[] = $cleanName;
                            }
                        }
                    }
                    // กำจัด duplicate แบบ case-insensitive
                    $temp = [];
                    foreach ($allAuthorsRaw as $name) {
                        $key = strtolower(trim($name));
                        $temp[$key] = $name;
                    }
                    $allAuthors = array_values($temp);

                    $x = 1;
                    $length = count($allAuthors);
                    foreach ($allAuthors as $rawName) {
                        $rawName = trim($rawName);
                        // ลบ trailing digits, asterisks และ whitespace (เช่น "No1", "*1")
                        $processedName = preg_replace('/[0-9\*\s]+$/', '', $rawName);
                        // สกัดเฉพาะตัวอักษรภาษาอังกฤษและช่องว่าง
                        $processedName = preg_replace('/[^A-Za-z\s]/', '', $processedName);
                        $processedName = trim($processedName);
                        if (empty($processedName)) {
                            continue;
                        }
                        // สมมุติว่ารูปแบบของรายชื่อ: "FirstName LastName"
                        $parts = preg_split('/\s+/', $processedName);
                        if (count($parts) < 2) {
                            continue;
                        }
                        $firstNameAuthor = array_shift($parts);
                        $lastNameAuthor = implode(' ', $parts);
                        if (empty($firstNameAuthor) || empty($lastNameAuthor)) {
                            continue;
                        }
                        // ตรวจสอบในตาราง User (สำหรับ teacher)
                        $existingUser = User::where('fname_en', $firstNameAuthor)
                            ->where('lname_en', $lastNameAuthor)
                            ->first();
                        if (!$existingUser) {
                            // ตรวจสอบในตาราง Author
                            $existingAuthor = Author::where('author_fname', $firstNameAuthor)
                                ->where('author_lname', $lastNameAuthor)
                                ->first();
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
                            // ใช้ syncWithoutDetaching เพื่อป้องกัน duplicate ใน pivot table
                            $paper->author()->syncWithoutDetaching([$authorId => ['author_type' => $authorType]]);
                        } else {
                            $authorType = ($x === 1) ? 1 : (($x === $length) ? 3 : 2);
                            $paper->teacher()->syncWithoutDetaching([$existingUser->id => ['author_type' => $authorType]]);
                        }
                        $x++;
                    }
                }
            } else {
                // หาก paper มีอยู่แล้ว ให้แนบ teacher (หากยังไม่ถูกผูก)
                $paper = $existingPaper;
                $hasTask = $user->paper()->where('paper_id', $paper->id)->exists();
                if (!$hasTask) {
                    $paper->teacher()->syncWithoutDetaching([$user->id]);
                }
            }
        }

        Log::info("TciScraperController: Imported paper count: " . $importedCount);
        return redirect()->back()->with('success', 'TCI records imported successfully.');
    }
}
