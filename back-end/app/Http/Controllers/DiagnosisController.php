<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Option;
use App\Models\Question;
use App\Models\Response;
use App\Models\Taker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosisController extends Controller
{
    public function index() 
    {
        // Retrieve all diagnoses with associated depression type and responses
        $diagnoses = Diagnosis::with(['depressionType', 'responses.option'])->get();

        // Check if there are diagnoses
        if ($diagnoses->count() > 0) {
            // Transform diagnoses to include depression type, sum of scores, and other information
            $diagnosesWithDetails = $diagnoses->map(function ($diagnosis) {
                $sumOfScores = $this->calculateSumOfScores($diagnosis);
                $depressionType = $this->getDepressionType($sumOfScores);

                return [
                    'id' => $diagnosis->id,
                    'taker' => $diagnosis->taker->first_name . ' ' . $diagnosis->taker->last_name,
                    'total_score' => $sumOfScores,
                    'depression_type' => $depressionType,
                    'responses' => $diagnosis->responses->map(function ($response) {
                        return [
                            'question' => $response->question->question,
                            'answer' => $response->option->option,
                            'score' => optional($response->option)->score,
                        ];
                    }),
                ];
            });

            return response()->json(['Diagnosis' => $diagnosesWithDetails]);
        } else {
            return response()->json(['diagnoses' => 'No Diagnosis Found']);
        }
    }

    public function create(Request $request, $id)
    {
        $taker = Taker::find($id);

        if (!$taker) {
            return response()->json([
                'message' => 'Taker not found',
            ]);
        }

        $takerID = $taker->id;

        $selectedOptions = array_values($request->all()); // Extract only the values

        // Get the number of questions in the database
        $numberOfQuestions = Question::count();

        // Validate the minimum size of selectedOptions
        if (count($selectedOptions) < $numberOfQuestions) {
            return response()->json([
                'message' => 'Invalid number of selected options. You need to select options for all questions.',
            ]);
        }

        // Get all options with a single query
        $options = Option::whereIn('id', $selectedOptions)->get();

        $diagnosis = Diagnosis::create(['takerID' => $takerID]);

        foreach ($options as $option) {
            // Create Response record
            Response::create([
                'diagnosisID' => $diagnosis->id,
                'questionID' => $option->questionID,
                'optionID' => $option->id,
            ]);
        }
        
        return response()->json([
            'message' => 'Diagnosis created successfully',
        ], 201);
    }

    public function read($id)
    {
        try {
            $diagnosis = Diagnosis::with(['depressionType', 'responses.option'])
                ->where('id', $id)
                ->firstOrFail();

            $diagnosisWithDetails = [
                'id' => $diagnosis->id,
                'taker' => $diagnosis->taker->first_name . ' ' . $diagnosis->taker->last_name,
                'taken_at' => $diagnosis->created_at->format('m/d/Y H:i A'),
                'total_score' => $this->calculateSumOfScores($diagnosis),
                'depression_type' => $this->getDepressionType($this->calculateSumOfScores($diagnosis)),
                'responses' => $diagnosis->responses->map(function ($response) {
                    return [
                        'question' => $response->question->question,
                        'answer' => $response->option->option,
                        'score' => optional($response->option)->score,
                    ];
                }),
            ];

            return response()->json(['diagnosis' => $diagnosisWithDetails]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Diagnosis Not Found'], 404);
        }
    }


    public function read_recent()
    {
        // Retrieve the latest diagnosis with associated depression type and responses
        $latestDiagnosis = Diagnosis::with(['depressionType', 'responses.option'])
            ->latest('created_at')
            ->first();

        if ($latestDiagnosis) {
            // Transform the latest diagnosis to include details
            $diagnosisWithDetails = [
                'id' => $latestDiagnosis->id,
                'taker' => $latestDiagnosis->taker->first_name . ' ' . $latestDiagnosis->taker->last_name,
                'taken_at' => $latestDiagnosis->created_at->format('m/d/Y H:i A'),
                'total_score' => $this->calculateSumOfScores($latestDiagnosis),
                'depression_type' => $this->getDepressionType($this->calculateSumOfScores($latestDiagnosis)),
                
                'responses' => $latestDiagnosis->responses->map(function ($response) {
                    return [
                        'question' => $response->question->question,
                        'answer' => $response->option->option,
                        'score' => optional($response->option)->score,
                    ];
                }),
            ];

            return response()->json(['diagnosis' => $diagnosisWithDetails]);
        } else {
            return response()->json(['message' => 'No recent diagnosis found']);
        }
    }

    public function read_taker_diagnoses($id) {
        $taker = Taker::find($id);
    
        if (!$taker) {
            return response()->json([
                'message' => 'Taker not found',
            ]);
        }
    
        // Retrieve all diagnoses for the specific taker with associated depression type and responses
        $diagnoses = Diagnosis::where('takerID', $taker->id)->with(['depressionType', 'responses.option'])->get();
    
        // Check if there are diagnoses
        if ($diagnoses->count() > 0) {
            // Transform diagnoses to include depression type, sum of scores, and other information
            $diagnosesWithDetails = $diagnoses->map(function ($diagnosis) {
                $sumOfScores = $this->calculateSumOfScores($diagnosis);
                $depressionType = $this->getDepressionType($sumOfScores);
    
                return [
                    'id' => $diagnosis->id,
                    'taker' => $diagnosis->taker->first_name . ' ' . $diagnosis->taker->last_name,
                    'total_score' => $sumOfScores,
                    'depression_type' => $depressionType,
                ];
            });
    
            return response()->json(['Diagnoses' => $diagnosesWithDetails]);
        } else {
            return response()->json(['diagnoses' => 'No Diagnosis Found']);
        }
    }
    

    private function calculateSumOfScores($diagnosis)
    {
        // Calculate the sum of scores for the associated options
        $sumOfScores = $diagnosis->responses->sum(function ($response) {
            return optional($response->option)->score ?? 0;
        });

        return $sumOfScores;
    }

    private function getDepressionType($sumOfScores)
    {
        // Retrieve the depression type and message based on the score range
        $result = DB::table('depression_types')
        ->where('scoreRangeStart', '<=', $sumOfScores)
        ->where('scoreRangeEnd', '>=', $sumOfScores)
        ->select('type', 'message')
        ->first();

        // If a result is found, return the type and message; otherwise, return null
        return $result ? ['type' => $result->type, 'message' => $result->message] : null;
    }    

}
