<?php
namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\ContentUpdateRequest;
use App\Models\AppSettings;
use App\Services\ContentSeeders\ContentSeederService;
use Illuminate\Support\Facades\Http;

class ContentUpdateController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(ContentUpdateRequest $request, ContentSeederService $contentSeederService)
    {
        $response = Http::timeout(120)->withOptions([
            'curl' => [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Avoid HTTP/2 reuse
            ],
        ])->get('https://admin.ilirski-bukvar.rs/api/content', [
            'content_version' => $request->content_version,
        ]);
        
        $content = $response->json()['data'] ?? null;
        
        if (empty($content) === true) {
            throw new \Exception('Could not retrieve data from API');
        }
        
        $contentSeederService->run($content['data']);

        AppSettings::query()->limit(1)->update([
            'content_version' => $content['version'],
        ]);

        return redirect()->to('/history');
    }
}