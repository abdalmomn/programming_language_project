<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\orderStatus;
use App\Models\status;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{

    public function showMedicines(){
        $medicine = Medicine::get([
        'id',
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
        ]);
        return $medicine;
    }
    
    public function insert(Request $request){
    $medicine = Medicine::Create($request->all());
    return response()->json([
    'message' => 'inserted successfully',
    'medicine ' => $medicine
    ]);
    }
    
    public function showCategory(Request $request){
    $categories = category::create($request->all());
    foreach ($categories as $category) {
        $medicine = Medicine::where('category' , 'like' , '%'. $category.'%')->get('tradeName');
    }
        return response()->json([
            'data' => [$categories ,$medicine],
        ]);
    }
    
    // public function showCategory(Request $request){
    //     $med = new Medicine();
    //     $medicine = Medicine::where('category' , 'like' , 'ادوية ضغط الدم')->get('tradeName');
    //     return response()->json([
    //     'data' => $medicine
    //     ]);
    // }
    public function details( request $request){
    $det=Medicine::where('id',$request->id)->get([
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
    ]);
    return $det;
    }
    
    
    public function search(request $request){
        $search=$request->search;
        $data=Medicine::where('TradeName' , 'like','%'. $search.'%')->get([
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
        ]);
        return response()->json([
            'data' => $data,
            ]);
    }
    
    
    
    
    
    
    
    public function order(Request $request)
    {
    $validator = Validator::make($request->all() , [
        'tradeName' => 'required',
        'quantity' => 'required|integer|min:1',
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
    }
    $medicine = $request->only('tradeName', 'quantity');

   // Check if the required quantity is available in the warehouse
    $warehouse_data = Medicine::where('tradeName', $medicine['tradeName'])->first();

    if ($warehouse_data && $warehouse_data->quantity >= $medicine['quantity']) {
       // if quantity is available, reduce the quantity from the warehouse
        $warehouse_data->decrement('quantity', $medicine['quantity']);
       // Check if there is an existing order for the same tradeName
        $existingOrder = Order::where('tradeName', $medicine['tradeName'])->latest()->first();

        if ($existingOrder) {
           // If there is an existing order, increment its quantity
            $existingOrder->increment('quantity', $medicine['quantity']);
        } else {
           // If there isn't an existing order, create a new one
            Order::create($medicine);
        }
    } else if(!$warehouse_data){
       // if quantity is not available, return a response to the pharmacist
        return response()->json([
            'error' => 'The medicine of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
        ]);
    }else{
        return response()->json([
            'error' => 'The quantity of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
        ]);
    }

    return response()->json([
        'success' => 'order has been added to cart'
    ]);
}

    
    public function showOrderInCart($id){
        $order = Order::select([
        'tradeName',
        'quantity',
        'status'
        ])->find($id);
        if ($order) {
            return response()->json([
            "data" => $order
            ]);
        }else {
            return response()->json([
            "message" => "the order is not found in our data",  
            ]);
        }
    }
    //show order by id one by one or all orders in one time or both
    
    
    
    
//     public function updateOrderStatus($id, $status){
//     $order = Order::find($id);
//     if ($order) {
//         $order->status = $status;
//         $order->save();
//         return response()->json([
//             "message" => "the order status has been updated",  
//         ]);
//     }else {
//         return response()->json([
//             "message" => "the order is not found in our data",  
//         ]);
//     }
// }

    public function updateOrderStatusAdmin($id, $status){
    $order = Order::find($id);
    if ($order) {
        $order->status = $status;
        $order->save();
        return response()->json([
            "message" => "the order status has been updated by admin",  
        ]);
    }else {
        return response()->json([
            "message" => "the order is not found in our data",  
        ]);
    }
}
    public function updatePaymentStatusAdmin($id, $status){
    $order = Order::find($id);
    if ($order) {
        $order->purchase = $status;
        $order->save();
        return response()->json([
            "message" => "the order status has been updated by admin",  
        ]);
    }else {
        return response()->json([
            "message" => "the order is not found in our data",  
        ]);
    }
}

    public function refreshData(){
    $orders = Order::all();
    return $orders;
}
    //idea: set 2 method ,one method for every status for admin and I already have a default status
}
