<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('document_file');
        $path = $file->storeAs("client-documents/{$client->id}", $file->getClientOriginalName(), 'public');

        ClientDocument::create([
            'client_id' => $client->id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'verification_status' => 'pending',
        ]);

        return redirect()->route('clients.show', $client)->with('success', 'Document uploaded.');
    }

    public function update(Request $request, ClientDocument $document)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('document_file');
        $path = $file->storeAs("client-documents/{$document->client_id}", $file->getClientOriginalName(), 'public');

        $document->update([
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'verification_status' => 'pending',
            'rejection_reason' => null,
            'verified_by' => null,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function verify(ClientDocument $document)
    {
        $document->update([
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Document verified.');
    }

    public function reject(Request $request, ClientDocument $document)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $document->update([
            'verification_status' => 'rejected',
            'verified_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Document rejected.');
    }
}
