<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\status;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{

    public function showMedicines(){
        $medicine = Medicine::get([
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantityAvailable',
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
    
    public function insert_categories(Request $request){
    $category = category::create($request->all());
    
    return response()->json([
    'message' => 'inserted successfully!',
    'data' => $category,
    ]);
    }
    
    public function showCategories(){
    
        
    }
    
    public function details( request $request){
    $det=Medicine::where('id',$request->id)->get();
    return $det;
    }
    
    
    public function search(request $request){
        $search=$request->search;
        $data=Medicine::where('TradeName','like','%'. $search.'%')->get();
        return response()->json([
            ' mess'=>$data,
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

    
    public function showOrderInCart(){
        // $status = status::find(1);
        $status = new status();
            $order = Order::get([
            
                'tradeName' ,
                'quantity'
            ]);
        return response()->json([
        'order' => $order,
        'status' => $status->status = 'in progress'
        ]);
    }
    // public function adminOrderIsSending(){
        
    //     $status = new status();
    //         $order = Order::get([
    //             'tradeName' ,
    //             'quantity'
    //         ]);
    //     return response()->json([
    //     'order' => $order,
    //     'status' => $status->status = 'sending'
    //     ]);
    // }
    public function adminOrderSent(Request $request){
    
    
    $orders = Order::all();

foreach ($orders as $order) {
    $order->status->status; // Access the order status
}

        // $status = new Order;
        // $order = Order::where('status_id' , 'id')->get([
        //     'tradeName' ,
        //     'quantity'
        // ]);
        // return response()->json([
        // 'order' => $order,
        // 'status' => $status->status_id,
        // ]);
        
        // $status = new Order();
        
        // if ($status->status_id == '1') {
        //     return response()->json([
        //     'order' => $order,
        //     'status' => 'the order is sending'
        //     ]);
        // }
        // else if ($status->status == 'sent') {
        //     return response()->json([
        //     'order' => $order,
        //     'status' => 'the order has been sent'
        //     ]);
        // }else {
        //     return response()->json([
        //     'order' => $order,
        //     'status' => 'the order in processing'
        //     ]);
        // }
    }
    //idea: set 2 method ,one method for every status for admin and I already have a default status
}
