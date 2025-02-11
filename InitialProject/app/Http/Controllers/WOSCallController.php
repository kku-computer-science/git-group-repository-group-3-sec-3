<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paper;
use App\Models\Source_data;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class WOSCallController extends Controller
{
    /**
     * Fetch data from Web of Science API based on author details.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);

        $fname = substr($user['fname_en'], 0, 1); // First initial of the first name
        $lname = $user['lname_en']; // Last name

        $apiKey = 'ab0a0497e0e1088891006ea420d2b85406b9cef7'; // Replace with your Web of Science API Key

        $response = Http::withHeaders([
            'X-ApiKey' => $apiKey,
        ])->get('https://api.clarivate.com/apis/wos-starter/v1/articles', [
            'usrQuery' => "AU=($lname, $fname)", // Query format for Web of Science
            'count' => 100,
        ])->json();

        // Check if the response contains data
        $articles = $response['Data'] ?? [];
        $this->saveArticles($articles);

        // Handle pagination if "next" URL exists
        if (isset($response['next'])) {
            $this->fetchNextPages($response['next']);
        }

        return redirect()->back();
    }

    /**
     * Save fetched articles into the database.
     *
     * @param array $articles
     */
    private function saveArticles($articles)
    {
        foreach ($articles as $article) {
            if (!isset($article['title'])) {
                continue;
            }

            if (Paper::where('paper_name', '=', $article['title'])->first() === null) {
                $paper = new Paper;
                $paper->paper_name = $article['title'];
                $paper->paper_sourcetitle = $article['sourceTitle'] ?? null;
                $paper->paper_yearpub = isset($article['publicationDate']) ? Carbon::parse($article['publicationDate'])->format('Y') : null;
                $paper->paper_doi = $article['doi'] ?? null;
                $paper->paper_url = $article['url'] ?? null;

                $paper->save();

                // Add source (Web of Science)
                $source = Source_data::where('source_name', 'Web Of Science')->first();
                if ($source) {
                    $paper->source()->attach($source->id);
                }

                // Add authors
                if (isset($article['authors'])) {
                    foreach ($article['authors'] as $author) {
                        $authorRecord = Author::firstOrCreate([
                            'author_fname' => $author['givenName'] ?? null,
                            'author_lname' => $author['familyName'] ?? null,
                        ]);
                        $paper->author()->attach($authorRecord->id);
                    }
                }
            }
        }
    }

    /**
     * Fetch paginated results from Web of Science API.
     *
     * @param string $nextUrl
     */
    private function fetchNextPages($nextUrl)
    {
        do {
            $response = Http::withHeaders([
                'X-ApiKey' => 'ab0a0497e0e1088891006ea420d2b85406b9cef7',
            ])->get($nextUrl)->json();

            if (isset($response['Data'])) {
                $this->saveArticles($response['Data']);
                $nextUrl = $response['next'] ?? null;
            } else {
                $nextUrl = null;
            }
        } while ($nextUrl);
    }

    /**
     * Display articles summary grouped by year.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $yearRange = range(Carbon::now()->year - 5, Carbon::now()->year);
    $papers = [];

    foreach ($yearRange as $year) {
        $papers[$year] = Paper::whereYear('paper_yearpub', $year)->count();
    }

    $scopusCount = Paper::whereHas('source', function ($query) {
        $query->where('source_name', 'Scopus');
    })->count();

    $wosCount = Paper::whereHas('source', function ($query) {
        $query->where('source_name', 'Web Of Science');
    })->count();

    $tciCount = Paper::whereHas('source', function ($query) {
        $query->where('source_name', 'TCI');
    })->count();

    $total = $scopusCount + $wosCount + $tciCount;

    return view('test', [
        'year' => json_encode(array_keys($papers), JSON_NUMERIC_CHECK),
        'paper' => json_encode(array_values($papers), JSON_NUMERIC_CHECK),
        'summary' => $total,
        'scopus' => $scopusCount,
        'wos' => $wosCount,
        'tci' => $tciCount,
    ]);
}

}
