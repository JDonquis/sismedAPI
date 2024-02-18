<?php 

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    protected $mainTable = '';
    
    protected $allowedParams = [];

    protected $tableMap = [];

    protected $columnMap = [];

    protected $orderByMap = [];


    public function getPaginateValues(Request $request,$table)
    {   
        $paginationArray = array('page' => 1,'rowsPerPage' => 25, 'orderBy' => $table.'.id', 'orderDirection' => 'desc');
        $currentPage = $request->query('page');
        $rowsPerPage = $request->query('rowsPerPage');
        $orderBy = $request->query('orderBy');
        $orderDirection = $request->query('orderDirection');

        if(isset($rowsPerPage))
            $paginationArray['rowsPerPage'] = $rowsPerPage;

        if(isset($currentPage))
            $paginationArray['page'] = $currentPage;

        if(isset($orderBy))
        {   
            $paginationArray['orderBy'] = $this->orderByMap[$orderBy];
        }


        if(isset($orderDirection))
            $paginationArray['orderDirection'] = $orderDirection;
        
        return $paginationArray;

    }

    public function transformParamsToQuery(Request $request)
    {
        $finalQuery = [];

        foreach ($request->query() as $key => $value) 
        {
            
            if(!isset($this->allowedParams[$key]))
                continue;

            $isAllowedParam = $this->allowedParams[$key];
            $table = $this->tableMap[$key] ?? $key;

            foreach($isAllowedParam as $field)
            {   
                if(isset($value[$field]))
                {   
                    $fieldMapped = $this->columnMap[$field] ?? $field;

                    if (strpos($value[$field], '[OR]') !== false):

                        $queries = $this->parseQuery($fieldMapped,$value[$field]);
                        $finalQuery = $this->addOrsQueries($finalQuery,$table,$queries);

                    elseif($table == 'search'):
                        $finalQuery = $this->handleFilterSearch($finalQuery, $value[$field]);
                    
                    else:
                        $finalQuery[$table][] = [$fieldMapped,'=',$value[$field]];
                    
                    endif;
                }

            }

        }


        return $finalQuery;
    }

    protected function parseQuery($paramName,$value)
    {
        $string = $value;
        $parts = preg_split("/\[(OR)\]/", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = [];

        for ($i = 0; $i < count($parts); $i += 2) 
        {   
                $result[] = [$paramName,'=', $parts[$i],'OR'];
        }
        
        return $result;
    }


    protected function addOrsQueries($finalQuery,$tableName,$queries)
    {
        foreach ($queries as $query)
        {
            $finalQuery[$tableName][] = $query;     
        }

        return $finalQuery;
    }

    protected function generateString($value) 
    {
      $words = explode(' ', $value);
      $result = '%';
      foreach ($words as $word) {
        $result .= $word . '%';
      }

      return $result;
    }


    protected function handleFilterSearch($query,$value)
    {   
          
            $tableName = $this->mainTable;

            $likeString = $this->generateString($value);

            $query[$tableName][] = ['search','LIKE',$likeString,'OR'];    
            
            return $query;
    }

}




