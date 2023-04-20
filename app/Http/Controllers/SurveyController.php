<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Models\Survey\Question;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;



class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get the current user from request
        $user = $request->user();
        // return Survey::where('user_id',$user->id)->paginate();
        //as we used SurveyResource, we can use it in here.
        // return SurveyResource::collection(Survey::where('user_id',$user->id)->paginate(2));
        return SurveyResource::collection(Survey::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(6));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        $data = $request->validated();

        //check if image was uploaded and save on local folder
        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
 
            $data['image'] = $relativePath;

        }
        $result = Survey::create($data);

        //create new questions
        foreach($data['questions'] as $question){
            $question['survey_id'] = $result->id;
            $this->createQuestion($question);
        }

        return new SurveyResource($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey,Request $request)
    {
        //get whether current user have permission to access survey
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(code: 403, message: 'Unauthorized action');
        }
        return new SurveyResource($survey);
        
    }

    public function showForGuest(Survey $survey)
    {  
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {            
        $data = $request->validated();

        //check if image was uploaded and save on local folder
        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            //if there is an old image, delete it
            if($survey->image){
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }

        }

        //update survey in the database
        $survey->update($data);

        //get ids as plain array of already added questions / existing questions
        $existingIds = $survey->questions()->pluck('id')->toArray();

        //get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'],'id');

        //find questions to delete
        $toDelete = array_diff($existingIds, $newIds);

        //find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        //delete questions by $toDelete array
        Survey\Question::destroy($toDelete);

        //create new questions
        foreach($data['questions'] as $question){
            if(in_array($question['id'], $toAdd)){
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        //update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach($survey->questions as $question){
            if(isset($questionMap[$question->id])){
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey,Request $request)
    {
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(code: 403, message: 'Unauthorized action');
        }
        
        //if there is an old image, delete it
        if($survey->image){
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }
        $survey->delete();
        return response(content:'',status:204);
    }
    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }

    private function createQuestion($question)
    {
        if(is_array($question['data'])){
            $question['data'] = json_encode($question['data']);
        }

        $validator = Validator::make($question,[
            'question' => 'required|string',
            'type' => ['required',Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);

        return Question::create($validator->validated());
    }

    private function updateQuestion(Survey\Question $question, $data)
    {
        if(is_array($data['data'])){
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data,[
            'id' => 'exists:App\Models\Survey\Question,id',
            'question' => 'required|string',
            'type' => ['required',Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);

        return $question->update($validator->validated());
    }
}
