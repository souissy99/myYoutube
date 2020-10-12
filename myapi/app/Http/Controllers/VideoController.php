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

    class VideoController extends Controller
    {
        public function uploadVideo(Request $request, $id)
        {
            if ((JWTAuth::parseToken()->authenticate() == User::find($id)) == 1) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|regex:/[a-zA-Z0-9_-]/',
                    'source' => 'required|file',
                ]);

                if($validator->fails()){
                        return response()->json($validator->errors()->toJson(), 400);
                }

                $path = public_path() . '/video';
                $mime = $request->file('source')->getMimeType();

                if ($mime == "video/x-flv" || $mime == "video/mp4" || $mime == "application/x-mpegURL" || $mime == "video/MP2T" || $mime == "video/3gpp" || $mime == "video/quicktime" || $mime == "video/x-msvideo" || $mime == "video/x-ms-wmv") 
                {
                    $res = DB::table('video')
                    ->insertGetId([
                        'name' => $request->get('name'),
                        'source' => $path . "/" . time() . '.' . $request->file('source')->getClientOriginalExtension(),
                        'user_id' => $id,
                        'view' => 0,
                        'enabled' => 0,
                    ]);

                    $data = DB::table('video')
                    ->where('id', $res)
                    ->get();

                    $request->file('source')->move($path, time() . '.' . $request->file('source')->getClientOriginalExtension());
                    $message = "Ok";

                    return response()->json(compact('message','data'), 201);
                } else
                    return response()->json("Mauvais type", 400);
            } else
                return response()->json("Mauvais user", 400);
        }

        public function getVideos(Request $request)
        {

            $offset = 0;
            $limit = 5;
            $message = "Ok";
            $validator = Validator::make($request->all(), [
                    'name' => 'string|regex:/[a-zA-Z0-9_-]/|max:255|',
                    'user' => 'int',
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

            if ($request->get('name') && $request->get('user') ) {
                    $data = DB::table('video')
                    ->where([
                        ['name', 'like', $request->get('name') . '%'],
                        ['user_id', $request->get('user')]
                    ])
                    ->orderBy('id', 'DESC')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                return response()->json(compact('message', 'data', 'pager'), 200);

            } else if ($request->get('name')) {
                    $data = DB::table('video')
                    ->where('name', 'like', $request->get('name') . '%')
                    ->orderBy('id', 'DESC')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                return response()->json(compact('message', 'data', 'pager'), 200);

            } else if ($request->get('user')) {
                    $data = DB::table('video')
                    ->where('user_id', $request->get('user'))
                    ->orderBy('id', 'DESC')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                return response()->json(compact('message', 'data', 'pager'), 200);

            } else {
                    $data = DB::table('video')
                    ->orderBy('id', 'DESC')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                return response()->json(compact('message', 'data', 'pager'), 200);
            }
        }

        public function getUserVideos(Request $request, $id)
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

            $data = DB::table('video')
            ->where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->offset($offset)
            ->limit($limit)
            ->get();

                return response()->json(compact('message', 'data', 'pager'), 200);
        }

        public function videoFormat(Request $request, $id)
        {
            $message = "Ok";
            $validator = Validator::make($request->all(), [
                    'code' => 'required|string',
                    'uri' => 'required|string',
                ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $res = DB::table('video_format')
            ->insertGetId([
                'code' => $request->get('code'),
                'uri' => $request->get('uri'),
                'video_id' => $id,
            ]);

            $data = DB::table('video')
            ->join('video_format', 'video_format.video_id', '=', 'video.id')
            ->where('video.id', $id)
            ->get();

            return response()->json(compact('message', 'data'), 200);
        }

        public function videoUpdate(Request $request, $id)
        {
            $message = "Ok";
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'view' => 'int',
                'user_id' => 'int',
                ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            if ($request->get('name')) {
                $data = DB::table('video')
                ->where('id', $id)
                ->update([
                    'name' => $request->get('name'),
                    ]);
                return response()->json(compact('message', 'data'), 200);

            } else if ($request->get('user_id')) {
                $data = DB::table('video')
                ->where('id', $id)
                ->update([
                    'user_id' => $request->get('user_id'),
                    ]);
                return response()->json(compact('message', 'data'), 200);
            } else if ($request->get('view')) {
                $data = DB::table('video')
                ->where('id', $id)
                ->update([
                    'view' => $request->get('view'),
                    ]);
                return response()->json(compact('message', 'data'), 200);
            } else {
                return response()->json(("Pas de data"), 400);
            }
        }

        public function videoDelete($id)
        {

            $toDelete = DB::table('video')
            ->where('id', $id)
            ->select('source')
            ->get();

            File::delete($toDelete[0]->source);

            $res = DB::table('video')
            ->where('id', $id)
            ->delete();

            return response()->json(("Deleted"), 204);
        }
    }
