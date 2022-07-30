<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
	/**
		* Display a listing of the resource.
		*
		* @return \Illuminate\Http\Response
		*/
	public function index()
	{
		try {
			$lessons = Lesson::query();
			return response()->json([
				'status' => 'success',
				'data' => $lessons->filter(request(['chapterId']))->get()
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
				'video' => 'required|string',
				'chapter_id' => 'required|integer'
			];

			$data = $request->all();

			$validator = Validator::make($data, $rules);
			if($validator->fails()) {
				return response()->json([
					'status' => 'error',
					'message' => $validator->errors()
				], 400);
			}
			$chapterId = $request->chapter_id;
			$chapter = Chapter::find($chapterId);
			if(!$chapter) {
				return response()->json([
					'status' => 'error',
					'message' => 'chapter not found'
				], 404);
			}
			$lesson = Lesson::create($data);
			return response()->json([
				'status' => 'success',
				'data' => $lesson
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
			$lesson = Lesson::find($id);
			if(!$lesson) {
				return response()->json([
					'status' => 'error',
					'message' => 'lesson not found'
				], 404);
			}

			return response()->json([
				'status' => 'success',
				'data' => $lesson
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
				'video' => 'string',
				'chapter_id' => 'integer'
			];

			$data = $request->all();
			$validator = Validator::make($data, $rules);
			if($validator->fails()) {
				return response()->json([
					'status' => 'error',
					'message' => $validator->errors()
				], 400);
			}
			$lesson = Lesson::find($id);
			if(!$lesson) {
				return response()->json([
					'status' => 'error',
					'message' => 'lesson not found'
				], 404);
			}

			$chapterId = $request->chapter_id;
			if($chapterId) {
				$chapter = Chapter::find($chapterId);
				if(!$chapter) {
					return response()->json([
						'status' => 'error',
						'message' => 'chapter not found'
					], 404);
				}
			}
			$lesson->update($data);
			return response()->json([
				'status' => 'success',
				'data' => $lesson
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
			$lesson = Lesson::find($id);
			if(!$lesson) {
				return response()->json([
					'status' => 'error',
					'message' => 'lesson not found'
				], 404);
			}
			$lesson->delete();
			return response()->json([
				'status' => 'success',
				'data' => $lesson
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => 'error',
				'message' => $th->getMessage()
			], 500);
		}
	}
}
