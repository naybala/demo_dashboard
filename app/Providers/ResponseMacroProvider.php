<?php
namespace App\Providers;

use App\Enums\Common\ResponseCode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

/**
 * Extend Response macros.
 *
 * @method static View successView(string $path, array $data)
 * @method static RedirectResponse successIndexRedirect(string $indexRoute, string $message)
 */
class ResponseMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('successView', fn(string $path, array $data): View => view($path, [
            'success' => true,
            'data'    => $data,
        ]));

        

        

        // Api Response
        Response::macro('sendAuthFailedResponse', function ($message, $data = null): JsonResponse {

            $response = [
                'code'    => ResponseCode::Unauthorized->responseCode(),
                'status'  => ResponseCode::Unauthorized->responseType(),
                'message' => $message,
            ];
            if ($data) {
                $response['data'] = $data;
            }
            return response()->json($response,ResponseCode::Unauthorized->responseCode());
        });

        Response::macro('sendSuccessResponse', function ($message, $data = null): JsonResponse {

            $response = [
                'code'    => ResponseCode::Success->responseCode(),
                'status'  => ResponseCode::Success->responseType(),
                'message' => $message,
            ];
            if ($data) {
                $response['data'] = $data;
            }
            return response()->json($response);
        });

        Response::macro('sendErrorResponse', function ($message): JsonResponse {
            $response = [
                'code'    => ResponseCode::InternalServerError->responseCode(),
                'status'  => ResponseCode::InternalServerError->responseType(),
                'message' => $message,
            ];
            return response()->json($response);
        });
    }
}
