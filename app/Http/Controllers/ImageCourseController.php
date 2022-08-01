<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ImageCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index()
  {
    return response()->json([
      'status' => 'error',
      'message' => 'not found'
    ], 404);
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
        'image' => 'required|url',
        'course_id' => 'required|integer'
      ];

      $data = $request->all();

      $validator = Validator::make($data, $rules);

      if($validator->fails()) {
        return response()->json([
          'status' => 'error',
          'message' => $validator->errors()
        ], 400);
      }

      $courseId = $request->course_id;
      $course = Course::find($courseId);

      if(!$course) {
        return response()->json([
          'status' => 'error',
          'message' => 'course not found'
        ], 404);
      }

      $imageCourse = ImageCourse::create($data);

      return response()->json([
        'status' => 'success',
        'data' => $imageCourse
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
    return response()->json([
      'status' => 'error',
      'message' => 'not found'
    ], 404);
  }

  /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function edit($id)
  {
    return response()->json([
      'status' => 'error',
      'message' => 'not found'
    ], 404);
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
    return response()->json([
      'status' => 'error',
      'message' => 'not found'
    ], 404);
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
      $imageCourse = ImageCourse::find($id);
      if(!$imageCourse) {
        return response()->json([
          'status' => 'error',
          'message' => 'image course not found'
        ], 404);
      }

      $imageCourse->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'image course deleted'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
    
  }
}
