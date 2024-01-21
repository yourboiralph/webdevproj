<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use App\Models\Option;
use App\Models\Response;
use Illuminate\Database\Seeder;

class DiagnosisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example takerID for the diagnosis
        $takerID = 1;

        $selectedAnswers = [
            1, 7, 9, 13, 17, 21, 25, 29, 33, 37, 41, 45, 49, 53, 57, 61, 65, 69, 73, 77, 84
        ];

        foreach ($selectedAnswers as $selectedAnswerID) {
            // Get the option details
            $option = Option::find($selectedAnswerID);

            if ($option) {
                // Create Diagnosis record if not exists
                $diagnosis = Diagnosis::firstOrNew(['takerID' => $takerID]);

                // Save the Diagnosis record
                $diagnosis->save();

                // Create Response record
                Response::create([
                    'diagnosisID' => $diagnosis->id,
                    'questionID' => $option->questionID,
                    'optionID' => $option->id,
                ]);
            }
        }
    }
}
