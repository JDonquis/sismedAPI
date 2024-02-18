<?php  

namespace App\Services;

use App\Exceptions\GeneralExceptions;
use App\Http\Resources\ConfigurationProductCollection;
use App\Http\Resources\ConfigurationProductResource;
use App\Models\Category;
use App\Models\Medicament;
use App\Models\TypeAdministration;
use App\Models\TypePresentation;
use DB;

class ConfigurationProductService
{	
	public function __construct()
    {
        $this->categoryModel = new Category;
        $this->typeAdministrationModel = new TypeAdministration;
        $this->typePresentationModel = new TypePresentation;
        $this->medicamentModel = new Medicament;
    }

    public function getAllCategories()
    {
        $categories = $this->categoryModel->orderBy('id','desc')->get();
        return new ConfigurationProductCollection($categories);
    }

    public function getAllTypePresentations()
    {
        $typePresentations = $this->typePresentationModel->orderBy('id','desc')->get();
        return new ConfigurationProductCollection($typePresentations);
    }
    
    public function getAllTypeAdministrations()
    {
        
        $typeAdministrations = $this->typeAdministrationModel->orderBy('id','desc')->get();
        return new ConfigurationProductCollection($typeAdministrations);
    }

    public function getAllTypeMedicaments()
    {
        $medicaments = $this->medicamentModel->orderBy('id','desc')->get();
        return new ConfigurationProductCollection($medicaments);
    }

    public function create($name,$type)
    {   
        $model = $this->getModel($type);

        if($model == null)
            throw new GeneralExceptions('Tipo de parametro no reconocido',400);

        $model->fill(['name' => $name]);
        $model->save();
        $model->fresh();

        return new ConfigurationProductResource($model);

    }

    

    public function update($name,$type,$id)
    {
        $model = $this->getModel($type);

        if($model == null)
            throw new GeneralExceptions('Tipo de parametro no reconocido',400);

        $config = $model->find($id);

        if(!isset($config->id))
            throw new GeneralExceptions('Registro no encontrado',404);

        $config->fill(['name' => $name]);
        $config->save();
        $config->fresh();

        return new ConfigurationProductResource($config);
    }

    public function delete($id,$type)
    {   
        $model = $this->getModel($type);
        if($model == null)
            throw new GeneralExceptions('Tipo de parametro no reconocido',400);

        $config = $model->find($id);

        if(!isset($config->id))
            throw new GeneralExceptions('Registro no encontrado',404);

        $config->delete();

        return 0;
    }

    
    private function getModel($type)
    {
        if($type == 1){
            return new Category;
        }
        else if($type == 2)
        {
            return new TypePresentation;
        }
        else if($type == 3)
        {
            return new TypeAdministration;
        }
        else if($type == 4)
        {
            return new Medicament;
        }
        else
        {
            return null;
        }
    }

}
