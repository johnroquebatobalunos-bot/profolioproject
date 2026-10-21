<?php

namespace App\Services;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class PortfolioPhotoStorage
{
    public function store(?UploadedFile $photo): ?string
    {
        if (! $photo) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $serviceKey = config('services.supabase.service_key');
        $bucket = config('services.supabase.storage_bucket');

        if ($baseUrl && $serviceKey && $bucket) {
            $path = 'profiles/'.Str::uuid().'.'.$photo->guessExtension();
            $photoPath = $photo->getRealPath();

            if ($photoPath === false || ($photoStream = fopen($photoPath, 'rb')) === false) {
                throw new RuntimeException('Unable to open the uploaded profile picture.');
            }

            try {
                $response = Http::withToken($serviceKey)
                    ->withHeaders(['Content-Type' => $photo->getMimeType()])
                    ->withBody(Utils::streamFor($photoStream), $photo->getMimeType())
                    ->post("{$baseUrl}/storage/v1/object/{$bucket}/{$path}");
            } finally {
                if (is_resource($photoStream)) {
                    fclose($photoStream);
                }
            }

            $response->throw();

            return "{$baseUrl}/storage/v1/object/public/{$bucket}/{$path}";
        }

        $path = $photo->storePublicly('profiles', 'public');

        return Storage::disk('public')->url($path);
    }

    public function delete(?string $photoUrl): void
    {
        if (! $photoUrl) {
            return;
        }

        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $serviceKey = config('services.supabase.service_key');
        $bucket = config('services.supabase.storage_bucket');
        $prefix = "{$baseUrl}/storage/v1/object/public/{$bucket}/";

        if ($baseUrl && $serviceKey && $bucket && Str::startsWith($photoUrl, $prefix)) {
            Http::withToken($serviceKey)->delete("{$baseUrl}/storage/v1/object/{$bucket}/".Str::after($photoUrl, $prefix));

            return;
        }

        $path = parse_url($photoUrl, PHP_URL_PATH);

        if (is_string($path) && Str::startsWith($path, '/storage/')) {
            Storage::disk('public')->delete(Str::after($path, '/storage/'));
        }
    }
}
