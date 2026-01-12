<?php

namespace App\Helpers;

class ViteHelper
{
    /**
     * Get the Vite CSS assets as HTML link tags
     */
    public static function cssAssets(): string
    {
        if (!file_exists(public_path('build/manifest.json'))) {
            return '';
        }

        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;

        if (!$cssFile) {
            return '';
        }

        return '<link rel="stylesheet" href="' . asset('build/' . $cssFile) . '">';
    }

    /**
     * Get the Vite JS assets as HTML script tags
     */
    public static function jsAssets(): string
    {
        if (!file_exists(public_path('build/manifest.json'))) {
            return '';
        }

        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;

        if (!$jsFile) {
            return '';
        }

        return '<script type="module" src="' . asset('build/' . $jsFile) . '"></' . 'script>';
    }
}
