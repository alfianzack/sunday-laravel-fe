<?php

if (!function_exists('getFullImageUrl')) {
    /**
     * Convert relative image URL to full URL
     * 
     * @param string|null $url
     * @return string|null
     */
    function getFullImageUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Get base API URL from config
        $baseUrl = config('services.api.url', 'http://localhost:5000/api');
        
        // Remove /api from base URL if present (we'll add it back if needed)
        $baseUrlWithoutApi = rtrim(str_replace('/api', '', $baseUrl), '/');
        
        // If URL starts with /api, remove it
        $url = ltrim($url, '/');
        if (str_starts_with($url, 'api/')) {
            $url = substr($url, 4);
        }
        
        // Combine base URL with image path
        return $baseUrlWithoutApi . '/' . ltrim($url, '/');
    }
}

