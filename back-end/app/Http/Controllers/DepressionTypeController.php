<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DepressionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepressionTypeController extends Controller
{
    public function index() 
    {
        $depressionTypes = DepressionType::all();

        if ($depressionTypes->count() > 0) {
            return response()->json(['depressionTypes' => $depressionTypes]);
        } else {
            return response()->json([
                'depressionTypes' => 'No Depression Type Found'
            ]);
        }
    }

    public function create(Request $request, $id) 
    {
        $admin = Admin::find($id); 

        if (!$admin) {
            return response()->json([
                'message' => 'Admin is required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|unique:depression_types',
                'scoreRangeStart' =>'required|numeric|min:1|unique:depression_types',
                'scoreRangeEnd' => [
                    'required',
                    'numeric',
                    'min:' . $request->scoreRangeStart,
                    'unique:depression_types'
                ],
                'message' => 'required|string|unique:depression_types',
            ]);     
            
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages(),
                ]);
            } else {
                $depressionTypes = DepressionType::create([
                    'type' => $request->type,
                    'scoreRangeStart' => $request->scoreRangeStart,
                    'scoreRangeEnd' => $request->scoreRangeEnd,
                    'message' => $request->message
                ]);

                if ($depressionTypes) {
                    return response()->json([
                        'message' => 'Depression Type Created Successfully',
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Something Went Wrong',
                    ]);
                }
            }
        }
    }

    public function read($id) 
    {
        $depressionType = DepressionType::find($id);

        if ($depressionType) {
            return response()->json([
                'depressionType' => $depressionType
            ]);
        } else {
            return response()->json([
                'message' => ' Depression Type Not Found'
            ]);
        }
    }

    public function update(Request $request, $d_id, $a_id) 
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'scoreRangeStart' => [
                'required',
                'numeric',
                'min:1',
            ],
            'scoreRangeEnd' => [
                'required',
                'numeric',
                'min:' . $request->scoreRangeStart,
            ],
            'message' => 'required|string'
        ]);     

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ]);
        } else {
            $depressionType = DepressionType::find($d_id);

            if ($depressionType) {
                $admin = Admin::find($a_id);

                if ($admin) {
                    $depressionType->update([
                        'type' => $request->type,
                        'scoreRangeStart' => $request->scoreRangeStart,
                        'scoreRangeEnd' => $request->scoreRangeEnd, 
                        'message' => $request->message
                    ]);

                    return response()->json([
                        'message' => 'Depression Type Updated Successfully'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Admin is Required'
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Depression Type Not Found'
                ]);
            }               
        }
    }


    public function delete($d_id, $a_id) 
    {
        $admin = Admin::find($a_id);

        if ($admin) {
            $depressionType = DepressionType::find($d_id);

            if ($depressionType) {
                $depressionType->delete();
    
                return response()->json([
                    'message' => 'Depression Type Deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'Depression Type Not Found'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Admin is Required'
            ]);
        }
    }
}
