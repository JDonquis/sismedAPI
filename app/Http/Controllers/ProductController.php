<?php

namespace App\Http\Controllers;

use App\Filters\ProductsQueryFilter;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Services\ConfigurationProductService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->productService = new ProductService;
        $this->queryFilter = new ProductsQueryFilter;
        $this->configurationProductService = new ConfigurationProductService;

    }

    public function index(Request $request)
    {   
        $queryArray = $this->queryFilter->transformParamsToQuery($request);

        $paginateArray = $this->queryFilter->getPaginateValues($request,'products');

        $products = $this->productService->getData($paginateArray,$queryArray);

        $productCollection = new ProductCollection($products);

        $total = $products->total();

        $relation = $request->query('relation') ?? "false";
        
        if($relation == "true")
        {   
            
            $categories = $this->configurationProductService->getAllCategories();
            $typePresentations = $this->configurationProductService->getAllTypePresentations();
            $typeAdministrations = $this->configurationProductService->getAllTypeAdministrations();
            $medicaments = $this->configurationProductService->getAllTypeMedicaments();
        }
        

        return [
            'products' => $productCollection,
            'categories' => $categories ?? null,
            'typePresentations' => $typePresentations ?? null,
            'typeAdministrations' => $typeAdministrations ?? null,
            'medicaments' => $medicaments ?? null,
            'total' => $total, 
            'message' => 'OK'
        ];

    }

    public function store(ProductRequest $request)
    {   
         
        try {
            
            $dataToCreateProduct = $this->productService->convertToSnakeCase($request);
            $response = $this->productService->create($dataToCreateProduct);

            return ['message' => $response['message'] ];
            
        } catch (Exception $e) {
            

                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());

            
            
        }
    }

    public function update(ProductRequest $request, Product $product)
    {

        $dataToUpdateProduct = $this->productService->convertToSnakeCase($request);

        try {

            $response = $this->productService->update($dataToUpdateProduct,$product);
            return ['message' => $response['message']];
            
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());
        }
    }

    public function destroy(Product $product)
    {
        try {

            $response = $this->productService->delete($product);
            
            return ['message' => $response['message']];

        }catch (Exception $e) {
            
            
                return response()->json([
                'status' => false,
                'message' => $e->getMessage()
                ], $e->getCode());

        }

    }

}
