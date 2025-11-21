<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api.url', 'http://localhost:5000/api');
    }

    protected function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $token = Session::get('api_token');
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception($response->json()['error'] ?? 'API request failed', $response->status());
    }

    // Auth API
    public function register(string $email, string $password, string $name)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/auth/register", [
                'email' => $email,
                'password' => $password,
                'name' => $name,
            ]);

        $data = $this->handleResponse($response);
        
        if (isset($data['token'])) {
            Session::put('api_token', $data['token']);
            Session::put('user', $data['user'] ?? null);
        }

        return $data;
    }

    public function login(string $email, string $password)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/auth/login", [
                'email' => $email,
                'password' => $password,
            ]);

        $data = $this->handleResponse($response);
        
        if (isset($data['token'])) {
            Session::put('api_token', $data['token']);
            Session::put('user', $data['user'] ?? null);
        }

        return $data;
    }

    public function logout()
    {
        Session::forget('api_token');
        Session::forget('user');
    }

    public function getCurrentUser()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/auth/me");

            if ($response->successful()) {
                $data = $response->json();
                Session::put('user', $data['user'] ?? null);
                return $data['user'] ?? null;
            }
        } catch (\Exception $e) {
            // Ignore errors
        }

        return null;
    }

    // Courses API
    public function getCourses()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/courses");

        return $this->handleResponse($response);
    }

    public function getCourse(string $id)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/courses/{$id}");

        return $this->handleResponse($response);
    }

    // Cart API
    public function getCart()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/cart");

        return $this->handleResponse($response);
    }

    public function addToCart(int $courseId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/cart/{$courseId}");

        return $this->handleResponse($response);
    }

    public function removeFromCart(int $courseId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete("{$this->baseUrl}/cart/{$courseId}");

        return $this->handleResponse($response);
    }

    // Orders API
    public function createOrder(array $data, $paymentProof = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let Guzzle set it for multipart
        
        $request = Http::withHeaders($headers);
        
        if ($paymentProof) {
            $request = $request->attach('payment_proof', file_get_contents($paymentProof->getRealPath()), $paymentProof->getClientOriginalName());
        }
        
        $response = $request->post("{$this->baseUrl}/orders", $data);

        return $this->handleResponse($response);
    }

    public function getOrders()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/orders");

        return $this->handleResponse($response);
    }

    // Enrollments API
    public function getEnrollments()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/enrollments");

        return $this->handleResponse($response);
    }

    public function getEnrollment(string $courseId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/enrollments/{$courseId}");

        return $this->handleResponse($response);
    }

    public function updateProgress(string $courseId, int $progress)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/enrollments/{$courseId}/progress", [
                'progress' => $progress,
            ]);

        return $this->handleResponse($response);
    }

    // Admin API
    public function getAdminOrders()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/admin/orders");

        return $this->handleResponse($response);
    }

    public function confirmPayment(int $orderId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->patch("{$this->baseUrl}/admin/orders/{$orderId}/confirm");

        return $this->handleResponse($response);
    }

    public function createCourse(array $data, $thumbnail = null, $previewVideo = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let Guzzle set it for multipart
        
        $request = Http::withHeaders($headers);
        
        if ($thumbnail) {
            $request = $request->attach('thumbnail', file_get_contents($thumbnail->getRealPath()), $thumbnail->getClientOriginalName());
        }
        
        if ($previewVideo) {
            $request = $request->attach('preview_video', file_get_contents($previewVideo->getRealPath()), $previewVideo->getClientOriginalName());
        }
        
        $response = $request->post("{$this->baseUrl}/admin/courses", $data);

        return $this->handleResponse($response);
    }

    public function updateCourse(int $courseId, array $data, $thumbnail = null, $previewVideo = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let Guzzle set it for multipart
        
        $request = Http::withHeaders($headers);
        
        if ($thumbnail) {
            $request = $request->attach('thumbnail', file_get_contents($thumbnail->getRealPath()), $thumbnail->getClientOriginalName());
        }
        
        if ($previewVideo) {
            $request = $request->attach('preview_video', file_get_contents($previewVideo->getRealPath()), $previewVideo->getClientOriginalName());
        }
        
        $response = $request->put("{$this->baseUrl}/admin/courses/{$courseId}", $data);

        return $this->handleResponse($response);
    }

    public function addVideoToCourse(int $courseId, array $data, $video = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let Guzzle set it for multipart
        
        $request = Http::withHeaders($headers);
        
        if ($video) {
            $request = $request->attach('video', file_get_contents($video->getRealPath()), $video->getClientOriginalName());
        }
        
        $response = $request->post("{$this->baseUrl}/admin/courses/{$courseId}/videos", $data);

        return $this->handleResponse($response);
    }

    public function updateVideo(int $courseId, int $videoId, array $data, $video = null)
    {
        $headers = $this->getHeaders();
        unset($headers['Content-Type']); // Let Guzzle set it for multipart
        
        $request = Http::withHeaders($headers);
        
        if ($video) {
            $request = $request->attach('video', file_get_contents($video->getRealPath()), $video->getClientOriginalName());
        }
        
        $response = $request->put("{$this->baseUrl}/admin/courses/{$courseId}/videos/{$videoId}", $data);

        return $this->handleResponse($response);
    }

    public function deleteVideo(int $courseId, int $videoId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->delete("{$this->baseUrl}/admin/courses/{$courseId}/videos/{$videoId}");

        return $this->handleResponse($response);
    }
}

