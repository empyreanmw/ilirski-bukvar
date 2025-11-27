<?php
namespace App\Http\Controllers\Actions;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Throwable;

class PromotionResponsesStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'uuid'     => ['required', 'uuid'],
            'enrolled' => ['required', 'boolean'],
            'rejected' => ['required', 'boolean'],

            'email' => [
                'nullable',
                'email',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
            'first_name' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
            'last_name' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
            'street' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
            'city' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('enrolled')),
            ],
        ]); 

        Promotion::create([
            'uuid' => $data['uuid'],
            'enrolled' => $data['enrolled'],
            'rejected' => $data['rejected']
        ]);

        if ($request->boolean('enrolled')) {
            $this->sendPromotionParticipantInfo($data);
        }

        return redirect()->back();
    }

    private function sendPromotionParticipantInfo($data)
    {
        try {
           Http::withOptions([
                'curl' => [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Avoid HTTP/2 reuse
                ],
            ])
            ->adminHeaders()
            ->post(
                'https://admin-staging.ilirski-bukvar.rs/api/promotion/participants',
                $data
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}