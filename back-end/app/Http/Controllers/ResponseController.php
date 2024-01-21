<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Option;
use App\Models\Taker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    public function index() 
    {
        $responses = Response::all();

        if ($responses->count() > 0) {
            return response()->json(['Response' => $responses]);
        } else {
            return response()->json([
                'responses' => 'No Response Found'
            ]);
        }
        
    }

    public function create(Request $request, $id)
    {
        $taker = Taker::find($id);

        if (!$taker) {
            return response()->json([
                'message' => 'Taker is required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'selectedAnswers' => 'array|min:21|required'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages(),
                ]);
            } else {
                $selectedAnswers = $request->input('selectedAnswers');
    
                foreach ($selectedAnswers as $selectedAnswerID) {
                    $option = Option::find($selectedAnswerID);
        
                    if ($option) {
                        Response::create([
                            'takerID' => $taker->id,
                            'questionID' => $option->questionID,
                            'answer' => $option->id,
                        ]);

                        return response()->json([
                            'message' => 'Diagnosed Successfully'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'Option Not Found',
                        ]);
                    }
            }
            }
        }
    }

    public function read($id) 
    {
    $Response = Response::find($id);

    if ($Response) {
        $relatedresponses = Response::where('created_at', $Response->created_at)
            ->where('takerID', $Response->takerID)
            ->get();

        $taker = Taker::find($Response->takerID);

        return response()->json([
            'Response' => $Response,
            'related_responses' => $relatedresponses,
            'taker' => $taker
        ]);
    } else {
        return response()->json([
            'message' => 'Response Not Found'
        ]);
    }
    }

    public function delete($id) {
        $Response = Response::find($id);

        if ($Response) {
            $Response->delete();

            return response()->json([
                'message' => 'Response Deleted Successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Response Not Found'
            ]);
        }
    }
}
