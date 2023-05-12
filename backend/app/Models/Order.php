<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'name', 'slug', 'is_active', 'status', 'amount', 'tax', 'total_amount'
    ];


    public static function getList()
    {
        $orders = self::paginate(10);
        $response['status'] = 'success';
        $response['message'] = 'Orders fetched successfully';
        $response['data'] = $orders;
        return $response;
    }


    public static function createItem($request)
    {
        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (isset($validation['status'])
            && $validation['status'] == 'failed'
        ) {
            return $validation;
        }

        $order = new self();
        $order->fill($inputs);
        $order->slug = Str::slug($inputs['name']);
        $order->save();
        
        $response['status'] = 'success';
        $response['message'] = 'Order created successfully';
        $response['data'] = $order;
        return $response;
    }

    public static function getItem($id)
    {
        $order = self::find($id);
        if (!$order) {
            $response['status'] = 'failed';
            $response['message'] = 'Order not found';
            return $response;
        }

        $response['status'] = 'success';
        $response['message'] = 'Order fetched successfully';
        $response['data'] = $order;
        return $response;
    }

    public static function updateItem($request, $id)
    {
        $inputs = $request->all();

        $validation = self::validation($inputs, $id);
        if (isset($validation['status'])
            && $validation['status'] == 'failed'
        ) {
            return $validation;
        }

        $order = self::find($id);
        $order->fill($inputs);
        $order->slug = Str::slug($inputs['name']);
        $order->save();

        $response['status'] = 'success';
        $response['message'] = 'Order updated successfully';
        $response['data'] = $order;
        return $response;
    }


    public static function itemAction($request)
    {
        $inputs = $request->all();
        $type = $inputs['type'];
        $items = $inputs['items'];
        $response = [];

        switch ($type) {
            case 'delete-all':
                self::withTrashed()->forceDelete();
                $response['status'] = 'success';
                $response['message'] = 'Orders deleted successfully';
            case 'trash-all':
                 self::query()->delete();
                $response['status'] = 'success';
                $response['message'] = 'Orders trashed successfully';
                break;
            case 'restore-all':
                self::withTrashed()->restore();
                $response['status'] = 'success';
                $response['message'] = 'Orders restored successfully';
                break;
            case 'active-all':
                self::withTrashed()->update(['is_active' => true]);
                $response['status'] = 'success';
                $response['message'] = 'Orders activated successfully';
                break;
            case 'inactive-all':
                self::withTrashed()->update(['is_active' => false]);
                $response['status'] = 'success';
                $response['message'] = 'Orders inactivated successfully';
                break;
            case 'delete':
                $ids = array_column($items, 'id');
                self::withTrashed()->whereIn('id', $ids)->forceDelete();
                $response['status'] = 'success';
                $response['message'] = 'Orders deleted successfully';
                break;
            case 'trash':
                $ids = array_column($items, 'id');
                $orders = self::withTrashed()->whereIn('id', $ids)->delete();
                $response['status'] = 'success';
                $response['message'] = 'Orders trashed successfully';
                break;
            default:
                $response['status'] = 'failed';
                $response['message'] = 'Invalid action type';
                break;
        }

        return $response;
    }

    public static function validation($inputs)
    {
        $rules = array(
            'name' => 'required|string|min:3,max:255',
            'is_active' => 'required|boolean',
            'status' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'tax' => 'required|numeric',
            'total_amount' => 'required|numeric',
        );

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $response['status'] = 'failed';
            $response['messages'] = $messages;
            return $response;
        }
    }

}
