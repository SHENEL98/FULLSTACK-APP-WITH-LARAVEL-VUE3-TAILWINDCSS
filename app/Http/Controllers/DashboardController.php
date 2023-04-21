<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Survey;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResource;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        //totla number of surveys
        $total = Survey::query()->where('user_id', $user->id)->count();

        //latest survey
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();

        //total number of answers
        $totalAnswers = Answer::query()
            ->join('surveys', 'answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->count();
         

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResource($latest) : null,
            'totalAnswers' => $totalAnswers
        ];
    }
}
