<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApplyApiController extends Controller
{
    private function portalBase(): string
    {
        return rtrim((string) env('PORTAL_API_URL', 'https://portal.curohelp.com'), '/');
    }

    public function options()
    {
        $url = $this->portalBase() . '/api/apply/options';
        $response = Http::acceptJson()->get($url);
        return $this->forwardResponse($response);
    }

    public function state(Request $request)
    {
        $candidate = $request->query('candidate');
        $url = $this->portalBase() . '/api/apply/state';
        $response = Http::acceptJson()->get($url, ['candidate' => $candidate]);
        return $this->forwardResponse($response);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file') ?: $request->file('filepond') ?: $request->file('upload');
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'No file provided'], 400);
        }

        $url = $this->portalBase() . '/api/apply/upload';

        $response = Http::acceptJson()
            ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post($url);

        return $this->forwardResponse($response);
    }

    public function submit(Request $request)
    {
        $payload = $request->all();
        $url = $this->portalBase() . '/api/apply/submit';
        $response = Http::acceptJson()->post($url, $payload);
        return $this->forwardResponse($response);
    }

    private function forwardResponse($response)
    {
        if ($response->successful()) {
            return response()->json($response->json(), $response->status());
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Portal API error',
            'error' => $response->json('error') ?? $response->body(),
        ], $response->status());
    }
}
