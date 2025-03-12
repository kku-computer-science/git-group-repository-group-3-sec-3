<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class UpdatePaperController extends Controller
{
    public function create($id)
    {
        // Decrypt the ID inside a try-catch block
        try {
            $decryptedId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }
        
        // Retrieve the user using the decrypted ID
        $data = User::find($decryptedId);
        if (!$data) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Call your Scopus update logic (ensure ScopuscallController exists and has a create() method)
        app(\App\Http\Controllers\ScopuscallController::class)->create($decryptedId);
        
        // Call your Scholar update logic (ensure ScholarCallController exists and has a create() method)
        app(\App\Http\Controllers\ScholarCallController::class)->create($decryptedId);
        
        // Redirect back with a success message using language translation
        return redirect()->back()->with('success', trans('dashboard.papers_updated_successfully'));
    }
}
