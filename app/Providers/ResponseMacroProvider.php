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

        Response::macro('successIndexRedirect', function (string $model, string $message, string $index = 'index'): RedirectResponse {
            $indexRoute = "$model.$index";
            return to_route($indexRoute)->with([
                'message'      => $message,
                'responseType' => ResponseCode::Success->responseType(),
            ]);
        });

        Response::macro('successShowRedirect', function (string $model, string $id, string $message, string $show = 'show'): RedirectResponse {
            $showRoute = "$model.$show";
            return to_route($showRoute, $id)->with([
                'message'      => $message,
                'responseType' => ResponseCode::Success->responseType(),
            ]);
        });

        Response::macro('redirectBackWithError', function ($repository, $message): RedirectResponse {
            $repository->rollBack();
            \Log::info($message);

            return redirect()->back()->with([
                'message'      => $message,
                'responseType' => ResponseCode::InternalServerError->responseType(),
            ]);
        });

        Response::macro('successAjaxResponse', function ($message, $data): JsonResponse {
            // \Log::info('hello world');
            $response = [
                'code'    => 200,
                'status'  => "Success",
                'message' => $message,
                'data'    => $data,
            ];
            return response()->json($response, 200);
        });

        Response::macro('notFoundAjaxRequest', function ($message): JsonResponse {
            $response = [
                'code'    => 404,
                'status'  => "Not Found",
                'message' => $message,
            ];
            return response()->json($response, 404);
        });
        
        Response::macro('failAjaxResponse', function ($message): JsonResponse {
            \Log::info($message);
            $response = [
                'code'    => 500,
                'status'  => "failed",
                'message' => $message,
            ];
            return response()->json($response, 500);
        });

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
