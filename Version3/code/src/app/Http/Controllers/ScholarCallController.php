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
use SerpApi\GoogleSearch;

class ScholarCallController extends Controller
{
    public function create($id)
    {
        // ถอดรหัส ID (ถอดรหัสเพียงครั้งเดียว)
        try {
            $decryptedId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }
        
        // ดึงข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ decrypted ID
        $data = User::find($decryptedId);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // ดึงค่า author_id จาก record ของผู้ใช้ โดยใช้ fallback หากชื่อ column ต่างกัน
        $author_id = $data->author_id ?? $data->authers_id ?? null;
        if (empty($author_id)) {
            \Log::error('Author ID is missing for user ID: ' . $decryptedId);
            return response()->json(['error' => 'Author ID is missing'], 400);
        }
        
        // สร้าง query สำหรับ Google Scholar Author API
        $query = [
            "engine"    => "google_scholar_author",
            "author_id" => $author_id,
            "num"       => 100,
        ];
        
        // สร้าง instance ของ GoogleSearch ด้วย API key ของคุณ
        $search = new GoogleSearch('9c6cfc17dff4bc7ef269e8dde08effc4503fc084807425c8f6f843319ffc5f9d');
        $result = $search->get_json($query);
        $articles = $result->articles ?? [];
        
        if (!empty($articles)) {
            foreach ($articles as $article) {
                // ตรวจสอบว่ามี Paper ที่มีชื่อเดียวกันอยู่แล้วหรือไม่
                if (Paper::where('paper_name', $article->title)->exists()) {
                    continue;
                }
                
                // สร้าง Paper ใหม่และ mapping ข้อมูลจาก API
                $paper = new Paper;
                $paper->paper_name        = $article->title;
                $paper->abstract          = null;
                $paper->paper_type        = null;
                $paper->paper_subtype     = null;
                $paper->paper_sourcetitle = isset($article->publication) ? $article->publication : null;
                $paper->keyword           = null;
                $paper->paper_url         = $article->link ?? null;
                $paper->paper_yearpub     = (isset($article->year) && is_numeric($article->year)) ? $article->year : null;
                $paper->paper_volume      = null;
                $paper->paper_issue       = null;
                $paper->paper_citation    = isset($article->cited_by->value) ? $article->cited_by->value : null;
                $paper->paper_page        = null;
                $paper->paper_doi         = null;
                $paper->paper_funder      = null;
                
                $paper->save();

                $paper->teacher()->syncWithoutDetaching([
                    $decryptedId => ['author_type' => 1] // or set the appropriate author_type
                ]);
                
                // Attach source "Google Scholar" ไปยัง Paper ผ่าน pivot table
                $source = Source_data::where('source_name', 'Google Scholar')->first();
                if ($source) {
                    $paper->source()->sync([$source->id]);
                } else {
                    \Log::error('Source "Google Scholar" not found in source_data table.');
                }
                
                // Process authors (รายชื่อผู้แต่ง) ถ้ามี
                if (isset($article->authors) && !empty($article->authors)) {
                    $authors_array = explode(',', $article->authors);
                    $x = 1;
                    $totalAuthors = count($authors_array);
    
                    foreach ($authors_array as $authorName) {
                        $authorName = trim($authorName);
                        $nameParts = explode(' ', $authorName);
                        if (count($nameParts) > 1) {
                            $givenName = array_shift($nameParts);
                            $surname = implode(' ', $nameParts);
                        } else {
                            $givenName = $authorName;
                            $surname = "";
                        }
                        
                        // ตรวจสอบในตาราง User ก่อน (ตรวจสอบจาก fname_en, lname_en)
                        $existingUser = User::where('fname_en', $givenName)
                            ->where('lname_en', $surname)
                            ->orWhere(function ($query) use ($givenName, $surname) {
                                $query->where(DB::raw("concat(left(fname_en,1),'.')"), '=', $givenName)
                                      ->where('lname_en', $surname);
                            })->first();
    
                        if (!$existingUser) {
                            // หากไม่พบใน User ให้ตรวจสอบในตาราง Author
                            $existingAuthor = Author::where('author_fname', $givenName)
                                ->where('author_lname', $surname)
                                ->first();
                            if (!$existingAuthor) {
                                // สร้าง Author ใหม่ถ้าไม่พบ
                                $newAuthor = new Author;
                                $newAuthor->author_fname = $givenName;
                                $newAuthor->author_lname = $surname;
                                $newAuthor->save();
                                $existingAuthor = $newAuthor;
                            }
                            
                            // กำหนดค่า author_type ตามตำแหน่งในรายชื่อ
                            $authorType = ($x === 1) ? 1 : (($x === $totalAuthors) ? 3 : 2);
                            
                            // Attach Paper กับ Author ผ่านความสัมพันธ์ author()
                            if (!$paper->author()->where('author_id', $existingAuthor->id)->exists()) {
                                $paper->author()->attach($existingAuthor->id, ['author_type' => $authorType]);
                            }
                        } else {
                            // หากพบใน User ให้ attach ผ่านความสัมพันธ์ teacher()
                            $authorType = ($x === 1) ? 1 : (($x === $totalAuthors) ? 3 : 2);
                            if (!$paper->teacher()->where('user_id', $existingUser->id)->exists()) {
                                $paper->teacher()->attach($existingUser->id, ['author_type' => $authorType]);
                            }
                        }
                        $x++;
                    }
                }
            }
        }
        
        return redirect()->back()->with('success', 'Articles processed successfully!');
    }
}
