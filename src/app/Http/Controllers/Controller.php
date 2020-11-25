<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

 /**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Project API documentation",
 *      description="Project API documentation",
 *      @OA\Contact(
 *          email="contact@vivifyideas.com"
 *      )
 * )
 */

 /**
 * @OA\Server(url="/api/")
 */

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
