<?php  

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Str;

class ApiService
{   
    protected $model;
    protected $snakeCaseMap = [];

    public function __construct($model)
    {
        $this->model = $model;
    }
    
    public function convertToSnakeCase($request)
    {   

        $fields = $request->all();
        $data = [];
        foreach ($fields as $field => $value) 
        {   
            
            $column = $this->snakeCaseMap[$field] ?? $field;

            $data[$column] = $value;
        }

        return $data;

    }
    

}
