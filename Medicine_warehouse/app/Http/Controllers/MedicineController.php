<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class MedicineController extends Controller
{
    
    public function showUserMedicines($adminId){
        $id = Auth::guard('api')->user()->id;
        $medicine = Medicine::where('user_id' , $adminId)->get();
        return $medicine;
}
    public function showAdminMedicines(){
        $id = Auth::guard('api')->user()->id;
        $medicine = Medicine::where('user_id' , $id)->get();
        return $medicine;
}

public function insert(Request $request){
    $request['user_id'] = Auth::guard('api')->user()->id;
    
    $validator = Validator::make($request->all() , [
        'theScientificName' => 'required',
        'tradeName' => 'required',
        'category' => 'required',
        'theManufactureCompany' => 'required',
        'quantity' => 'required',
        'validity' => 'required',
        'price' => 'required',
    ]);
    if($validator->fails()){
        return response()->json($validator->errors(), 422);
    }
    $medicine = Medicine::Create($request->all());
    return response()->json([
        'message' => 'inserted successfully',
        'medicine ' => $medicine
    ]);
    }
    
    
    public function details(Request $request){
    $det = Medicine::where('id',$request->id)->get([
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
    ]);
    return response()->json([
    'data' => $det,
    ]);
    }
    
    public function search(request $request){
        $search=$request->search;
        $data=Medicine::where('TradeName' , 'like','%'. $search.'%')->orwhere('category' , 'like','%'. $search.'%')->get(['tradeName' , 'category']);
        return response()->json([
            'data' => $data,
            ]);
    }
    
    public function Order(Request $request)
    {
    $userId = Auth::user()->id;
    
    $validator = Validator::make($request->all(), [
        'data' => 'required|array',
        'data.*.medicine_id' => 'required|integer|exists:medicines,id',
        'data.*.quantity' => 'required|integer|min:1'
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400);
    }

    $order = Order::create([
        'user_id' => $userId
    ]);

    foreach ($request->data as $value) {
        $medicine = Medicine::find($value['medicine_id']);
        if ($medicine->quantity >= $value['quantity']) {
            $orderUser = UserOrder::create([
                'order_id' => $order->id,
                'medicine_id' => $value['medicine_id'],
                'quantity' => $value['quantity'],
            ]);
            $medicine->decrement('quantity', $value['quantity']);
        } else {
            $order->delete();
            return response()->json(['message' => 'We do not have enough quantity for medicine ID ' . $value['medicine_id']]);
        }
    }
    return response()->json(['message' => 'Done'], 200);
}

    public function viewUserOrders($userId, $orderId){
        $order = Order::where('user_id', $userId)
            ->where('id', $orderId)
            ->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $products = UserOrder::where('order_id', $orderId)
            ->join('medicines', 'medicine_order.medicine_id', '=', 'medicines.id')
            ->select('medicines.*', 'medicine_order.quantity')
            ->get();
        $responseData = [
                'order_details' => [
                'user_id' => $order->user_id,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
            ],
            'medicines' => $products,
        ];

        return response()->json($responseData);
    }
    public function viewAdminOrders()
    {
        $adminId = Auth::user()->id; 
        
        $orders = Order::whereHas('medicines', function ($query) use ($adminId) {
            $query->where('medicines.user_id', $adminId);
            })->with('medicines')->get();

        return response()->json($orders);
    }
    
    
    public function updateOrderStatus($orderId, Request $request)
    {
        $adminId = Auth::user()->id;
        
        $order = Order::whereHas('medicines', function ($query) use ($adminId) {
            $query->where('medicines.user_id', $adminId);
        })->find($orderId);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found or not assigned to the admin'], 404);
        }
        
        $order->payment_status = $request->input('payment_status', $order->payment_status);
        $order->order_status = $request->input('order_status', $order->order_status);
        $order->save();
        
        return response()->json(['message' => 'Order status updated successfully']);
    }
}