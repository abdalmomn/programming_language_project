<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function insert(Request $request){
    $medicine = Medicine::Create($request->all());
    
    category::insert(['categories'=>$request->category]);
    
    
    $duplicated = DB::table('categories')
            ->select('categories', DB::raw('count(`categories`) as occurences'))
            ->groupBy('categories')
            ->having('occurences', '>', 1)
            ->get();
foreach ($duplicated as $duplicate) {
   // Get the row you don't want to delete.
    $dontDeleteThisRow = category::where('categories', $duplicate->categories)->first();

   // Delete all rows except the one we fetched above.
    category::where('categories', $duplicate->categories)->where('id', '!=', $dontDeleteThisRow->id)->delete();
}

    return response()->json([
    'message' => 'inserted successfully',
    'medicine ' => $medicine
    ]);
    
    
    
    }
    public function details(Request $request){
        
        $cat = category::query()->get(['categories']);
        $details = Medicine::where('category' , '=' , 'ادوية سكر')->get([
        'tradeName',
        ]);
        return $details;
    }
}
