<?php

namespace Database\Seeders;

use App\Models\DepressionType;
use Illuminate\Database\Seeder;

class DepressionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $depressionTypes = [
            ['type' => 'Normal', 'scoreRangeStart' => 1, 'scoreRangeEnd' => 10, 'message' => "That's good. You are in a normal range of emotional well-being. Continue to prioritize your mental health."],
            ['type' => 'Mild', 'scoreRangeStart' => 11, 'scoreRangeEnd' => 16, 'message' => "If you are experiencing Mild depression, consider reaching out to a mental health professional for guidance and support."],
            ['type' => 'Borderline', 'scoreRangeStart' => 17, 'scoreRangeEnd' => 20, 'message' => "If you are experiencing Borderline depression, seeking professional help is crucial. Reach out to a mental health professional or counselor."],
            ['type' => 'Moderate', 'scoreRangeStart' => 21, 'scoreRangeEnd' => 30, 'message' => "If you are experiencing Moderate depression, please reach out to a mental health professional as soon as possible for guidance and treatment."],
            ['type' => 'Severe', 'scoreRangeStart' => 31, 'scoreRangeEnd' => 40, 'message' => "If you are experiencing Severe depression, it's critical to seek immediate professional help. Contact a mental health crisis hotline or visit an emergency room."],
            ['type' => 'Extreme', 'scoreRangeStart' => 41, 'scoreRangeEnd' => 100, 'message' => "If you are experiencing Extreme depression, urgent intervention is needed. Please seek immediate help from a mental health professional or contact emergency services."],
        ];

        foreach ($depressionTypes as $type) {
            DepressionType::create($type);
        }
    }

}
