<?php

namespace App\Http\Controllers\admin\v1;

use Exception;
use App\Models\Product;
use App\Traits\utilities;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Interfaces\ProductInterface;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\admin\v1\ProductRequest;



class ProductController extends Controller
{
    use ApiResponse, Docs, utilities;
    private  $userRepository;
    private  $productRepository;
   // private $activityRepository;
    public function __construct(UserInterface $userRepository, ProductInterface $productRepository)
    {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        //$this->activityRepository = $activityRepository;
        
        $this->middleware('auth.apikey');
        $this->middleware('auth:api');
      
    }

    // --- index
    /**
     
     * @OA\Get(
     *      path="/admin/v1/get-products",
     *      operationId="getAllProducts",
     *      tags={"Product"},
     *      security={{ "APIKey": {} }},
     *
     *      @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *
     *     )
     */
    public function index(){
      try{
          $products = Product::get();
          return $this->successResponse(new ProductCollection($products));
      }
      catch(Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
      }
  }


      /**
     
     * @OA\Post(
     * path="/admin/v1/store-product",
     * tags={"Product"},
     * security={{ "APIKey": {} }},
     *    @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to update activity weekdays",
     *            @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                  @OA\Property(property="title", description="title"),
     *                  @OA\Property(property="product_subtitle",type="object",
     *                  @OA\Property(property="english",type="string"),
     *                  @OA\Property(property="frensh",type="string"),
     *                  @OA\Property(property="espagnole",type="string"),
     *                          
     *                     ),
     *                  @OA\Property(property="description",type="object",
     *                  @OA\Property(property="english",type="string"),
     *                  @OA\Property(property="frensh",type="string"),
     *                  @OA\Property(property="espagnole",type="string"),
     *                       
     *                     ),
     *                  
     *                  @OA\Property(property="price", description="price", type="double"),
     *                  @OA\Property(property="image",description="file to upload",format="binary",type="string"),
     *                  
     *                 ),
     *            ),
     * ),
     *          @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *        ),
     *
     *
     *       @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *

     * )
     *
      */

      // == old version
 /*
    public function store(Request $request)
  {
    // $data = json_decode([$request->product_subtitle,$request->description], true);
    $img = $this->generateImageURL($request);
    $user = $this->userRepository->getSelf();
    $productSubtitleArray = json_decode($request->product_subtitle, true);
    $descriptionArray = json_decode($request->description, true);
    // return  $productSubtitleArray ["frensh"];

    //the translatable package ken aam be nazelon bel database as {"en":"test"} bas l gharib eno kelon keno 3am ynzalo as en

    $product = Product::create([
      "title" => $request->title,
      "subtitle" => $productSubtitleArray["english"],
      "description" => $descriptionArray["english"],
      "price" => $request->price,
      "featuring_img" => $img,
      "user_id" => $user->id
    ]);

    foreach ($productSubtitleArray as $key => $value) {
      switch ($key) {
        case 'frensh':
          $local = 'fr';
          break;

        case 'espagnole':
          $local = 'es';
          break;

        default:
          $local = 'en';
      }


      // $product->setTranslation("subtitle",$local, $productSubtitleArray [$key]);
      // $product->setTranslation("description",$local, $descriptionArray [$key]);
      // $product->save(); 
      $translation = new ProductTranslation([
        "locale" => $local,
        "subtitle" => $productSubtitleArray[$key],
        "description" => $descriptionArray[$key]
      ]);
      $product->translations()->save($translation);

    }
    //$product->getTranslation('subtitle', 'fr');


    return $this->successResponse($product->translations()->get());
  }
  */

 
    /**
     * new version
     * @OA\Post(
     * path="/admin/v1/store-product",
     * tags={"Product"},
     * security={{ "APIKey": {} }},
     *    @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to update activity weekdays",
     *            @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                  @OA\Property(property="title", description="title"),
     *                  @OA\Property(property="language", description="lang"),
     *                  @OA\Property(property="product_subtitle",type="string",description="subtitle"),
     *                  @OA\Property(property="description",type="string",description="description"),
     *                  @OA\Property(property="price", description="price", type="double"),
     *                  @OA\Property(property="image",description="file to upload",format="binary",type="string"),
     *                  
     *                 ),
     *            ),
     * ),
     *          @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *        ),
     *
     *
     *
     *
     *       @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *

     * )
     *
      */

  public function store(ProductRequest $request)
  {
    try {
      $img = $this->generateImageURL($request);
      $user = $this->userRepository->getSelf();


      $product = Product::create([
        "title" => $request->title,
        "price" => $request->price,
        "featuring_img" => $img,
        "user_id" => $user->id
      ]);

      $this->productRepository->createTranslation($request, $product);
      $p = Product::where('id',$product->id)->first();
     
      return $this->successResponse(new ProductResource($p));
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
 
 * @OA\Put(
 *     path="/admin/v1/update-product-language/{product_slug}",
 *     tags={"Product"},
 *     security={{ "APIKey": {} }},
 *      @OA\Parameter(
 *         name="product_slug",
 *         in="path",
 *         description="the product to update",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *       @OA\Parameter(
 *         name="x-language",
 *         in="header",
 *         description="the product to update",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *    @OA\RequestBody(
 *           required=true,
 *           description="Body request needed to update activity weekdays",
 *            @OA\MediaType(
 *            mediaType="application/json",
 *            @OA\Schema(
 *               type="object",
 *          
 *                  @OA\Property(property="product_subtitle",type="string"),
 *                  @OA\Property(property="description",type="string"),
 *                     ),
 *                 ),
 *            ),
 *      @OA\Response(
 *          response="200",
 *          description="Successful Operation",
 *          @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="success", type="boolean", description="status" ),
 *          @OA\Property(property="data", type="object", description="data" ),
 *          @OA\Property(property="message", type="string", description="message" ),
 *          ),
 *        ),
 *       @OA\Response(
 *          response="422",
 *          description="Unprocessable Entity",
 *          @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="success", type="boolean", description="status" ),
 *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
 *          @OA\Property(property="message", type="string", description="message" ),
 *          ),
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request",
 *          @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="success", type="boolean",      description="status" ),
 *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
 *          @OA\Property(property="message", type="string", description="message" ),
 *          ),
 *       ),
 * )
 */
  public function updateTranslation(ProductRequest $request)
  {
    try { 

      $product = $this->productRepository->getProduct($request->product_slug);
      if (isset($product)) {
        $this->productRepository->createTranslation($request, $product);
      } else
        return $this->successResponse("product inactive");

      $a = Product::where("id", $product->id)->first(); 
      return $this->successResponse(new ProductResource($a));
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
