<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index()
  {
    try {
      $chapters = Chapter::query();

      return response()->json([
        'status' => 'success',
        'data' => $chapters->filter(request(['courseId']))->get()
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
        'course_id' => 'required|integer'
      ];

      $data = $request->all();

      $validator = Validator::make($data, $rules);

      if ($validator->fails()) {
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

      $chapter = Chapter::create($data);
      return response()->json([
        'status' => 'success',
        'data' => $chapter
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
    try {
      $chapter = Chapter::find($id);
      if (!$chapter) {
        return response()->json([
          'status' => 'error',
          'message' => 'chapter not found'
        ], 404);
      }

      return response()->json([
        'status' => 'success',
        'data' => $chapter
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
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
    try {
      $rules = [
        'name' => 'string',
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
      $chapter = Chapter::find($id);

      if(!$chapter) {
        return response()->json([
          'status' => 'error',
          'message' => 'chapter not found'
        ], 404);
      }
      
      $courseId = $request->course_id;
      $course = Course::find($courseId);
      if(!$course) {
        return response()->json([
          'status' => 'error',
          'message' => 'course not found'
        ], 404);
      }


      $chapter->update($data);
      return response()->json([
        'status' => 'success',
        'data' => $chapter
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
      $chapter = Chapter::find($id);
      if (!$chapter) {
        return response()->json([
          'status' => 'error',
          'message' => 'chapter not found'
        ], 404);
      }

      $chapter->delete();
      return response()->json([
        'status' => 'success',
        'message' => 'chapter deleted'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => 'error',
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
