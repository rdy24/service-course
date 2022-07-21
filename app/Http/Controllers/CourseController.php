<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentor;
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
      ]);
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
      ]);
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
      //
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
      ]);
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
  }
}
