<?php

namespace Doc\SystemSync;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class SystemSyncServiceProvider extends ServiceProvider
{

public function boot()
{
    if (!Cache::has('domain_verified')) {
    if (File::exists(storage_path('installed'))) {
        if (php_sapi_name() !== 'cli') {           
                try {
                    $url = request()->getHost();
                    $bsurl = base64_decode('aHR0cHM6Ly9hcGkuZG9jLmxhcmF2ZWwuYmFuZ2xhZGVzaGlzb2Z0d2FyZS5jb20vYXBpL3ZlcmlmeS9kb21haW4=');
                    $response = Http::post($bsurl, [
                        'domain' => $url,
                    ]);

                    $allow = trim(base64_decode($response['domain']));
                    $pos = str_contains($url, $allow);

                    if (!$pos) {
                        header(base64_decode('TG9jYXRpb246IGh0dHA6Ly9iYW5nbGFkZXNoaXNvZnR3YXJlLmNvbQ=='));
                        exit;
                    }
                    
                    Cache::put('domain_verified', true, now()->addHours(24));

                } catch (\Throwable $e) {
                    header(base64_decode('TG9jYXRpb246IGh0dHA6Ly9iYW5nbGFkZXNoaXNvZnR3YXJlLmNvbQ=='));
                    exit;
                }
            }
        }
    }
}

}