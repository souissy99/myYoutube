<?php

    namespace App\Http\Controllers;
    
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Collection as BaseCollection;
    use Illuminate\Support\Str;
    use Illuminate\Support\Traits\ForwardsCalls;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Support\Facades\File; 
    use JWTAuth;

    class CommentController extends Controller
    {
        public function postComment(Request $request, $id)
        {
            $userId = JWTAuth::parseToken()->authenticate()->id;
            $validator = Validator::make($request->all(), [
                'body' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

                $res = DB::table('comment')
                ->insertGetId([
                    'body' => $request->get('body'),
                    'video_id' => $id,
                    'user_id' => $userId,
                ]);

                $data = DB::table('comment')
                ->where('id', $res)
                ->get();

                $message = "Ok";

                return response()->json(compact('message','data'), 201);
        }

        public function getComment(Request $request, $id)
        {
            $offset = 0;
            $limit = 5;
            $message = "Ok";
            $validator = Validator::make($request->all(), [
                    'page' => 'int',
                    'perPage' => 'int',
                ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            if ($request->get('perPage')) $limit = $request->get('perPage');
            if ($request->get('page')) $offset = $request->get('page') * $limit;

            $pager = [
                    "current" => (int)$offset,
                    "total" => (int)$limit
            ];

                $data = DB::table('comment')
                ->orderBy('id', 'DESC')
                ->offset($offset)
                ->limit($limit)
                ->get();

            return response()->json(compact('message', 'data', 'pager'), 201);

        }
    }