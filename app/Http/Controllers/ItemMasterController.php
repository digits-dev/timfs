<?php

namespace App\Http\Controllers;

use App\ItemMaster;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemMasterController extends Controller
{
    public function getItems(Request $request){
        try{
            $request->validate([
                'datefrom' => ['required', 'date_format:Y-m-d H:i:s', 'before:dateto'],
                'dateto'   => ['required', 'date_format:Y-m-d H:i:s', 'after:datefrom'],
            ], [
                'datefrom.before' => 'The datefrom must be before the dateto.',
                'dateto.after'    => 'The dateto must be after the datefrom.',
            ]);

            $data = ItemMaster::getItems()
            ->whereBetween('item_masters.approved_at_1', [$request->datefrom, $request->dateto])
            ->whereNotNull('item_masters.tasteless_code')
            ->where('item_masters.action_type','like', '%Create%')
            ->orderBy('item_masters.tasteless_code','ASC')->paginate(50);

            return response()->json([
                'api_status' => 1,
                'api_message' => 'success',
                'records' => $data,
                'http_status' => 200
            ],200);
        }
        catch(ValidationException $ex){
            return response()->json([
                'api_status' => 0,
                'api_message' => 'Validation failed',
                'errors' => $ex->errors(),
                'http_status' => 401
            ], 401);
        }
    }

    public function getUpdatedItems(Request $request){
        try{
            $request->validate([
                'datefrom' => ['required', 'date_format:Y-m-d H:i:s', 'before:dateto'],
                'dateto'   => ['required', 'date_format:Y-m-d H:i:s', 'after:datefrom'],
            ], [
                'datefrom.before' => 'The datefrom must be before the dateto.',
                'dateto.after'    => 'The dateto must be after the datefrom.',
            ]);

            $data = ItemMaster::getUpdatedItems()
            ->whereBetween('item_masters.updated_at', [$request->datefrom, $request->dateto])
            ->whereNotNull('item_masters.tasteless_code')
            ->orderBy('item_masters.tasteless_code','ASC')->paginate(50);

            return response()->json([
                'api_status' => 1,
                'api_message' => 'success',
                'records' => $data,
                'http_status' => 200
            ],200);
        }
        catch(ValidationException $ex){
            return response()->json([
                'api_status' => 0,
                'api_message' => 'Validation failed',
                'errors' => $ex->errors(),
                'http_status' => 401
            ], 401);
        }
    }
}
