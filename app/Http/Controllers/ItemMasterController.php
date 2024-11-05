<?php

namespace App\Http\Controllers;

use App\MenuItem;
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

            $data = MenuItem::getItems()
            ->whereBetween('menu_items.created_at', [$request->datefrom, $request->dateto])
            ->whereNotNull('menu_items.tasteless_menu_code')
            ->where('menu_items.approval_status','1')
            ->where('menu_items.status','ACTIVE')
            ->where('menu_items.action_type','Create')
            ->orderBy('menu_items.tasteless_menu_code','ASC')->paginate(50);

            unset($data['links']);

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

            $items = MenuItem::getUpdatedItems()
            ->whereBetween('menu_items.updated_at', [$request->datefrom, $request->dateto])
            ->whereNotNull('menu_items.tasteless_menu_code')
            ->orderBy('menu_items.tasteless_menu_code','ASC')->paginate(50);
            
            $data = $items->toArray();
            unset($data['links']);

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
