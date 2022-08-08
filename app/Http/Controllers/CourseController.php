<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index()
  {
    try {
      $courses = Course::query();

      return response()->json([
        'status' => 'success',
        'data' => $courses->filter(request(['name', 'status']))->paginate(10)->withQueryString(),
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
  }

  /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function create()
  {
    return response()->json([
      'status' => 'error',
      'message' => 'not found'
    ], 404);
  }

  /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function store(Request $request)
  {
    try {
      $rules = [
        'name' => 'required|string',
        'certificate' => 'required|boolean',
        'thumbnail' => 'string|url',
        'type' => 'required|in:free,premium',
        'status' => 'required|in:draft,published',
        'price' => 'integer',
        'level' => 'required|in:all-level,beginner,intermediate,advance',
        'mentor_id' => 'required|integer',
        'description' => 'string'
      ]; 

      $data = $request->all();

      $validator = Validator::make($data, $rules);

      if($validator->fails()){
        return response()->json([
          'status' => 'error',
          'message' => $validator->errors()
        ], 400);
      }

      $mentorId = $request->mentor_id;
      $mentor = Mentor::find($mentorId);
      if(!$mentor){
        return response()->json([
          'status' => 'error',
          'message' => 'mentor not found'
        ], 404);
      }

      $course = Course::create($data);
      return response()->json([
        'status' => 'success',
        'data' => $course
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
  }

  /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function show($id)
  {
    $course = Course::find($id);
    if(!$course){
      return response()->json([
        'status' => 'error',
        'message' => 'course not found'
      ], 404);
    }

    $reviews = Review::where('course_id', $id)->get()->toArray();

    if(count($reviews) > 0){
      $userIds = array_column($reviews, 'user_id');
      $users = getUserByIds($userIds);

      if($users['status'] === 'error') {
        $reviews = [];
      } else {
        foreach($reviews as $key => $review) {
          $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
          $reviews[$key]['users'] = $users['data'][$userIndex];
        }
      }
    }

    $totalStudent = MyCourse::where('course_id', $id)->count();
    $totalVideos = Chapter::where('course_id', $id)->withCount('lessons')->get()->toArray();
    $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

    $course['reviews'] = $reviews;
    $course['total_student'] = $totalStudent;
    $course['total_videos'] = $finalTotalVideos;
    return response()->json([
      'status' => 'success',
      'data' => $course
    ]);
  }

  /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function edit($id)
  {
      //
  }

  /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function update(Request $request, $id)
  {
    try {
      $rules = [
        'name' => 'string',
        'certificate' => 'boolean',
        'thumbnail' => 'string|url',
        'type' => 'in:free,premium',
        'status' => 'in:draft,published',
        'price' => 'integer',
        'level' => 'in:all-level,beginner,intermediate,advance',
        'mentor_id' => 'integer',
        'description' => 'string'
      ];

      $data = $request->all();

      $validator = Validator::make($data, $rules);

      if($validator->fails()){
        return response()->json([
          'status' => 'error',
          'message' => $validator->errors()
        ], 400);
      }

      $course = Course::find($id);
      if(!$course){
        return response()->json([
          'status' => 'error',
          'message' => 'course not found'
        ], 404);
      }

      $mentorId = $request->mentor_id;
      if($mentorId) {
        $mentor = Mentor::find($mentorId);
        if(!$mentor){
          return response()->json([
            'status' => 'error',
            'message' => 'mentor not found'
          ], 404);
        }
      }
      $course->update($data);
      return response()->json([
        'status' => 'success',
        'data' => $course
      ]);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy($id)
  {
    try {
      $course = Course::find($id);

      if(!$course){
        return response()->json([
          'status' => 'error',
          'message' => 'course not found'
        ], 404);
      }

      $course->delete();

      return response()->json([
        'status' => 'success',
        'data' => 'course deleted'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
    
  }
}
