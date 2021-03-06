<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    protected $paginationSize = 200;
    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();

    public function index()
    {
        $data = !$this->paginationSize ? $this->model()::all() : $this->model()::paginate($this->paginationSize);
        return $data;
    }

    public function store(Request $request){
        $validatedData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validatedData);
        $obj->refresh();
        return $obj;
    }

    protected function findOrFail($id){
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        return $this->model()::where($keyName,$id)->firstOrFail();
    }

    public function update(Request $request, $id){
        $obj = $this->findOrFail($id);
        $validateData = $this->validate($request,$this->rulesUpdate());
        $obj->update($validateData);
        return $obj;
    }

    public function show($id){
        $obj = $this->findOrFail($id);
        return $obj;
    }

    public function destroy($id){
       $obj = $this->findOrFail($id);
       $obj->delete();
       return response()->noContent();
    }
}
