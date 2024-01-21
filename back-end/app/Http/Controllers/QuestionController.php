<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index() 
    {
        $questions = Question::all();

        if ($questions->count() > 0) {
            return response()->json(['questions' => $questions]);
        } else {
            return response()->json([
                'questions' => 'No Questions Found'
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
                'question' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages(),
                ]);
            } else {
                $question = Question::create([
                    'adminID' => $admin->id,
                    'question' => $request->question
                ]);
    
                if ($question) {
                    return response()->json([
                        'message' => 'Question Created Successfully',
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
        $question = Question::find($id);

        if ($question) {
            return response()->json([
                'question' => $question
            ]);
        } else {
            return response()->json([
                'message' => 'Question Not Found'
            ]);
        }
    }

    public function update(Request $request, $q_id, $a_id) 
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ]);
        } else {
            $question = Question::find($q_id);

            if ($question) {

                $admin = Admin::find($a_id);

                if ($admin) {
                    $question->update([
                        'adminID' => $admin->id,
                        'question' => $request->question
                    ]);
    
                    return response()->json([
                        'message' => 'Admin Updated Successfully'
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Admin is Required'
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Question Not Found'
                ]);
            }               
        }
    }

    public function read_question_options() 
    {
        $questions = Question::with('options')->get();

        if ($questions->count() > 0) {
            return response()->json(['questions' => $questions]);
        } else {
            return response()->json([
                'questions' => 'No Questions Found'
            ]);
        }
    }

    public function delete($q_id, $a_id) 
    {
        $admin = Admin::find($a_id);

        if ($admin) {
            $question = Question::find($q_id);

            if ($question) {
                $question->delete();

                return response()->json([
                    'message' => 'Question Deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'Question Not Found'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Admin is Required'
            ]);
        }
    }
}
