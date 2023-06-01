<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UrlRequest;
use App\Models\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UrlController extends Controller
{

    /**
     * @param Url $urlData
     * @return bool
     */
    private function IsUrlSingleUse(Url $urlData): bool
    {
        return ($urlData->single_use === 1 && $urlData->click_counts >= 1);
    }

    /**
     * @param UrlRequest $request
     * @return JsonResponse
     */
    public function CreateHashUrl(UrlRequest $request): JsonResponse
    {
        \Log::info('In ' . __METHOD__);
        $urlRequestData = $request->validated();
        try{

            $hashedUrl = Url::hashUrl($urlRequestData['long_url']);

            $checkIfHashAlreadyExists = Url::where('hashed_url',$hashedUrl)->first();

            if($checkIfHashAlreadyExists) {
                return response()->json([
                    'success'       => false,
                    'hashed_url'    => url($hashedUrl),
                    'message'       => 'Hashed URL exists already',
                ], 400);
            }
            $urlData = Url::create([
                'hashed_url'    => $hashedUrl,
                'long_url'      => $urlRequestData['long_url'],
                'single_use'    => $urlRequestData['single_use'],
                'click_counts'  => 0,
            ]);

            return response()->json([
                'success'       => true,
                'hashed_url'    => url($urlData->hashed_url),
                'message'       => 'Hashed URL successfully generated',
            ], 201);

        } catch(\Exception $e) {

            \Log::error($e);
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @param string $hashedUrl
     * @return RedirectResponse
     */
    public function RedirectToUrl(string $hashedUrl): RedirectResponse
    {
        \Log::info('In ' . __METHOD__);
        $fetchUrlData = Url::where('hashed_url', $hashedUrl)->first();

        if(!$fetchUrlData) {
            throw new NotFoundHttpException();
        }

        try{

            $checkIfUrlIsSingleUse = $this->IsUrlSingleUse($fetchUrlData);
            if($checkIfUrlIsSingleUse) {
                abort(400, 'URL is single use');
            }

            $fetchUrlData->update([
                'click_counts'   => $fetchUrlData->click_counts + 1
            ]);

            return redirect()->to($fetchUrlData->long_url);
        } catch(\Exception $e) {

            \Log::error($e);
            abort(500);

        }

    }

    /**
     *
     */
    public function GetHashedUrlData(string $hashedUrl): JsonResponse
    {
        \Log::info('In ' . __METHOD__);
        $findHashedUrlData = Url::where('hashed_url', $hashedUrl)->first();

        if($findHashedUrlData) {

            return response()->json([
                'success'   => true,
                'message'   => 'Data fetched successfully',
                'data'      => $findHashedUrlData
            ], 200);

        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'No data found for ' . url($hashedUrl),
            ], 404);
        }
    }
}
