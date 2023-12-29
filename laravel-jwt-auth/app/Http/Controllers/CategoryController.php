<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $category = category::create($request->all());
        return response()->json($category,200);
    }


    // public function update(Request $request , $id){
    //     $category = category::find($id);
    //     $category->update($request->all());

    //     return response()->json($category,200);
    // }

    public function delete($id){
        $category = category::find($id);
        if ($category) {
        $category->delete();
            return response()->json(['message' => 'category has been deleted successfully!'],200);
        }else {
            return response()->json(['message' => 'the category has already deleted'],200);
            
        }
    }


    public function index(){
        $data = category::get(['categories']);
        return $data;
    }  
    
    public function medicines(Request $request){
        
        $data = Medicine::where('category',$request->cat_name)->latest()->get(['tradeName']);

        return response()->json($data,200);

    }


}
