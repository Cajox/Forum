<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Http\Request;

abstract class Filters{

    /**
     * @var Request
     */
    protected $request, $builder;

    protected $filters = [];

    public function __construct(Request $request){

        $this->request = $request;

    }

    public function apply($builder){

        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value){

            if (method_exists($this, $filter)){

                $this->$filter($value);

            }

        }

/*        $this->getFilters()
            ->filter(function ($filter){
                return method_exists($this,$filter);
            })
            ->each(function ($filter, $value){
                $this->$filter($value);
            });

        return $this->builder; */
        return $this->builder;

    }

    public function getFilters(){

        return $this->request->only/*intersect*/($this->filters);

        // return collect($this->request->intersect/*intersect*/($this->filters))->flip();

    }

}
