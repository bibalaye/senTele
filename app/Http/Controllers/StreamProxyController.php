<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StreamProxyController extends Controller
{
    /**
     * Proxy pour les flux HLS - Résout les problèmes CORS
     */
    public function proxy(Channel $channel)
    {
        // Vérifier que l'utilisateur a accès à cette chaîne
        if (!$channel->is_active) {
            abort(404);
        }

        // Récupérer le manifest m3u8
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => request()->headers->get('referer', config('app.url')),
                ])
                ->get($channel->stream_url);

            if ($response->failed()) {
                abort(502, 'Impossible de récupérer le flux');
            }

            // Retourner le contenu avec les bons headers
            return response($response->body())
                ->header('Content-Type', 'application/vnd.apple.mpegurl')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        } catch (\Exception $e) {
            abort(502, 'Erreur lors de la récupération du flux: ' . $e->getMessage());
        }
    }

    /**
     * Proxy pour les segments TS
     */
    public function segment(Request $request)
    {
        $url = $request->query('url');
        
        if (!$url) {
            abort(400, 'URL manquante');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($url);

            if ($response->failed()) {
                abort(502, 'Impossible de récupérer le segment');
            }

            return response($response->body())
                ->header('Content-Type', 'video/mp2t')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Cache-Control', 'public, max-age=3600');

        } catch (\Exception $e) {
            abort(502, 'Erreur segment: ' . $e->getMessage());
        }
    }
}
