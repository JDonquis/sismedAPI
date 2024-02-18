<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\ConfigurationProductCollection;
use App\Http\Resources\ConfigurationProductResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Medicament;
use App\Models\Product;
use App\Models\TypeAdministration;
use App\Models\TypePresentation;
use App\Services\ApiService;
use DB;

class ProductService extends ApiService
{   

    protected $snakeCaseMap = [

        'minimumStock' => 'minimum_stock',
        'categoryId' => 'category_id',
        'typePresentationId' => 'type_presentation_id',
        'typeAdministrationId' => 'type_administration_id',
        'medicamentId' => 'medicament_id',
        'unitPerPackage' => 'unit_per_package',
        'concentrationSize' => 'concentration_size',
    ];


    public function __construct()
    {
        $this->categoryModel = new Category;
        $this->typeAdministrationModel = new TypeAdministration;
        $this->typePresentationModel = new TypePresentation;
        $this->medicamentModel = new Medicament;
        $this->productModel = new Product;
        parent::__construct(new Product);

    }


    public function getData($paginateArray, $queryArray)
    {   

         $products = Product::select([
            'products.*',
            'categories.name as category_name',
            'type_administrations.name as type_administration_name',
            'type_presentations.name as type_presentation_name',
            'medicaments.name as medicament_name',

        ])
        ->join('categories','products.category_id','=','categories.id')
        ->join('type_presentations','products.type_presentation_id','=','type_presentations.id')
        ->join('type_administrations','products.type_administration_id','=','type_administrations.id')
        ->join('medicaments','products.medicament_id','=','medicaments.id');
        
            foreach ($queryArray as $table => $array )
            {       

                if($table == 'search')
                    $table = 'products';
                
                foreach ($array as $params)
                {   
                    if(isset($params[3]))
                        $products = $products->orWhere($table.'.'.$params[0],$params[1],$params[2]);    
                    else
                        $products = $products->where($table.'.'.$params[0],$params[1],$params[2]);    

                }
            }


        $products = $products->orderBy($paginateArray['orderBy'],$paginateArray['orderDirection'])
        ->paginate($paginateArray['rowsPerPage'], ['*'], 'page', $paginateArray['page']);

        return $products;

    }

    public function create($data)
    {  
        $lastProduct = $this->productModel->orderBy('code','desc')->first(); 
        $code = $lastProduct->code + 1;
        $data['code'] = $code;
        $this->productModel->fill($data);
        $this->productModel->save();
        return ['message' => 'Producto creado exitosamente'];

    }

    public function update($dataToUpdateProduct,$product)
    {
        
        $product->fill($dataToUpdateProduct);
        $product->save();

        return ['message' => 'Producto actualizado exitosamente'];

    }

    public function delete($product)
    {   
        $product->delete();
        return ['message' => 'Producto Eliminado exitosamente'];

        return 0;
    }

    protected function handleParams($mainTableParams,$entity)
    {
        return $this->model
        ->where(function ($query) use ($mainTableParams) {


            foreach ($mainTableParams as $key => $queryParams) 
            {   
                if(isset($queryParams[3]))
                    continue;

                $query->where($queryParams[0], $queryParams[1], $queryParams[2]);                    
                unset($mainTableParams[$key]);
            }
            
        })
        ->where(function($query) use ($mainTableParams) {
            foreach ($mainTableParams as $queryParams) 
            {   
                $query->orWhere(function($query) use ($queryParams) 
                {
                    $query->orWhere($queryParams[0], $queryParams[1], $queryParams[2]);
                });
            }
        
        });
    }


}
