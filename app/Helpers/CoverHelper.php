<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Log;

class CoverHelper
{
    public string $serverEndpoint = 'http://localhost:5010/';
    public string $url;
    public int $id;
    public string $directoryPath;

    public function __construct($url, $id, $directoryPath)
    {
        $this->url = $url;
        $this->id = $id;
        $this->directoryPath = $directoryPath;
    }

    public function getCoverImage()
    {
        $url = $this->url;
        $id = $this->id;
        $directoryPath = $this->directoryPath;

        $endpoints = [
            'https://bunkr.si/v/' => 'get_cover_bunkr',
            'https://bunkr.si/a/' => 'get_cover_album_bunkr',
            'https://pixeldrain.com/l/' => 'get_cover_album_pixeldrain',
            'https://pixeldrain.com/u/' => 'get_cover_pixeldrain',
            'https://gofile.io/d/' => 'get_cover_album_gofile',
            'https://saint2.su/embed/' => 'get_cover_saint2',
        ];

        foreach ($endpoints as $key => $endpoint) {
            if (strpos($url, $key) !== false) {
                return $this->processCoverRequest($endpoint, $url, $id, $directoryPath);
            }
        }

        return ['type' => 'danger', 'message' => 'URL not supported!!'];
    }

    private function processCoverRequest($endpoint, $url, $id, $directoryPath)
    {
        // First check if the server is online
        try {
            $response = Http::timeout(60)->get($this->serverEndpoint);
            $this->checkResponse($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ['type' => 'danger', 'message' => 'Server offline?'];
        }
        try {
            $response = Http::timeout(90)->post("{$this->serverEndpoint}{$endpoint}", ['url' => $url]);

            $this->checkResponse($response);
            $cover = $response->json()[0]['base64'];
            if ($cover === null) {
                return ['type' => 'danger', 'message' => 'Cover image not found!!'];
            }

            ImageHelper::processCoverImage($cover, $id, $directoryPath);
            return ['type' => 'success', 'message' => 'Cover image updated successfully!!'];
        } catch (\Exception $e) {
            return ['type' => 'danger', 'message' => $e->getMessage()];
        }
    }

    private function checkResponse($response)
    {
        if ($response->status() !== 200 || empty($response->json())) {
            return ['type' => 'danger', 'message' => 'Server offline?'];
        }
    }
}
