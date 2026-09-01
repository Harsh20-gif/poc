<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('lead')->orderBy('created_at', 'desc')->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['lead', 'documents.verifier', 'certifications']);
        return view('clients.show', compact('client'));
    }

    public function updateStatus(Request $request, Client $client)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:pending,scheduled,completed',
            'survey_date' => 'nullable|date',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)->with('success', 'Verification status updated.');
    }
}
