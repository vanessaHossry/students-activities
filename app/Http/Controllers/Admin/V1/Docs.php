<?php

namespace App\Http\Controllers\Admin\V1;

/**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Laravel OpenApi Demo Documentation for admin",
     *      description="L5 Swagger OpenApi description for admin",
     * 
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * ),
     * 
     * @OA\SecurityScheme(
     *      securityScheme="APIKey",
     *      type="apiKey",
     *      in="header",
     *      name="X-Authorization"
     * )
     *
*/

trait Docs{}
