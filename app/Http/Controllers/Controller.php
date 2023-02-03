<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * a simple validation method for controller functions
     *
     * @param string $fn
     * @param array $rules
     * @param array $messages
     *
     * @return void
     */
    public function validate(string $fn, array $rules, array $messages = []): void
    {
        $this->middleware(function (Request $request, Closure $next) use ($rules, $messages) {
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                $errors = collect($validator->errors());

                return response()->json([
                    "type" => "Invalid data",
                    "message" => $validator->errors()->first(),
                    "errors" => $errors->map(fn($error) => $error[0])
                ], 400);
            } else {
                return $next($request);
            }
        })
            ->only($fn);
    }
}
