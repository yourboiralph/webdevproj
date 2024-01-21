<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OptionController extends Controller
{
    public function index() 
    {
        $options = Option::all();

        if ($options->count() > 0) {
            return response()->json(['options' => $options]);
        } else {
            return response()->json([
                'options' => 'No Options Found'
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
                'questionID' => 'required|numeric|min:1',
                'option' => 'required|string',
                'score' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:3',
                    
                    Rule::unique('options')->where(function ($query) use ($request) {
                        return $query->where('questionID', $request->questionID);
                    }),
                ],
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages(),
                ]);
            } else {
                $question = Question::find($request->questionID);

                if ($question) {
                    $option = Option::create([
                        'adminID' => $admin->id,
                        'questionID' => $question->id,
                        'option' => $request->option,
                        'score' => $request->score
                    ]);
        
                    if ($option) {
                        return response()->json([
                            'message' => 'Option Created Successfully',
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Something Went Wrong',
                        ]);
                    }

                } else {
                    return response()->json([
                        'message' => 'Question Not Found',
                    ]);
                }
                
                }
        }
    }


    public function read($id) 
    {
        $option = Option::find($id);

        if ($option) {
            return response()->json([
                'option' => $option
            ]);
        } else {
            return response()->json([
                'message' => 'Option Not Found'
            ]);
        }
    }

    public function update(Request $request, $o_id, $a_id) 
    {
        $validator = Validator::make($request->all(), [
            'questionID' => 'required|numeric|min:1',
            'option' => 'required|string',
            'score' => [
                'required',
                'numeric',
                'min:0',
                'max:3',
                
                Rule::unique('options')->where(function ($query) use ($request) {
                    return $query->where('questionID', $request->questionID);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ]);
        } else {
            $question = Question::find($request->questionID);

            if ($question) {
                $option = Option::find($o_id);

                if ($option) {
                    $admin = Admin::find($a_id);

                    if ($admin) {
                        $option->update([
                            'adminID' => $admin->id,
                            'questionID' => $question->id,
                            'option' => $request->option,
                            'score' => $request->score
                        ]);
        
                        return response()->json([
                            'message' => 'Option Updated Successfully'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Admin is Required'
                        ]);
                    }
                } else {
                    return response()->json([
                        'message' => 'Option Not Found'
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Question Not Found'
                ]);
            }               
        }
    }

    public function delete($o_id, $a_id) 
    {
        $admin = Admin::find($a_id);

        if ($admin) {
            $option = Option::find($o_id);

            if ($option) {
                $option->delete();
    
                return response()->json([
                    'message' => 'Option Deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'Option Not Found'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Admin is Required'
            ]);
        }
    }
}
