<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index()
  {
    try {
      $myCourses = MyCourse::query();

      return response()->json([
        'status' => 'success',
        'data' => $myCourses->filter(request(['user_id']))->get()
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
      'message' => 'Not Found'
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
        'course_id' => 'required|integer',
        'user_id' => 'required|integer'
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

      if (!$course) {
        return response()->json([
          'status' => 'error',
          'message' => 'course not found'
        ], 404);
      }

      $userId = $request->user_id;
      $user = getUser($userId);

      if ($user['status'] === 'error') {
        return response()->json([
          'status' => $user['status'],
          'message' => $user['message']
        ], $user['http_code']);
      }
      
      $isExistMyCourse = MyCourse::where('course_id', '=', $courseId)->where('user_id', '=', $userId)->exists();

      if ($isExistMyCourse) {
        return response()->json([
          'status' => 'error',
          'message' => 'my course already exist'
        ], 400);
      }

      if ($course->type === 'premium') {
            if ($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price can\'t be 0'
                ], 405);
            }
            
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);

            if ($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            return response()->json([
                'status' => $order['status'],
                'data' => $order['data']
            ]);
        } else {
            $myCourse = MyCourse::create($data);

            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }

      $myCourse = MyCourse::create($data);
      return response()->json([
        'status' => 'success',
        'data' => $myCourse
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
      'message' => 'Not Found'
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
      'message' => 'Not Found'
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
      'message' => 'Not Found'
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
    return response()->json([
      'status' => 'error',
      'message' => 'Not Found'
    ], 404);
  }

  public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
