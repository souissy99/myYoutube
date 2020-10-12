<?php

    namespace App\Http\Controllers;

    use App\User;
    use App\Token;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class UserController extends Controller
    {
        public function authenticate(Request $request)
        {
            $credentials = $request->only('username', 'password');

            try {
                if (! $data = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }


            $exp = JWTAuth::setToken($data)->getPayload()->get('exp');
            $date = Carbon::createFromTimestamp($exp)->toDateTimeString();
            $date = Carbon::parse($date)->addHour(2);

            Token::setToken(auth()->user()->id, $data, $date);
            $message = "Ok";
            return response()->json(compact('message','data'), 201);
        }

        public function register(Request $request)
        {
                $validator = Validator::make($request->all(), [
                'username' => 'required|string|regex:/[a-zA-Z0-9_-]/|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'pseudo' => 'string|max:255',
                'password' => 'required|string|confirmed',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $data = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'pseudo' => $request->get('pseudo'),
                'password' => Hash::make($request->get('password')),
            ]);
            $message = "Ok";
            $token = JWTAuth::fromUser($data);

            return response()->json(compact('message','data'), 201);
        }

        public function getAuthenticatedUser()
        {
                try {
                      if (! $user = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                      }
                } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                        return response()->json(['token_expired'], $e->getStatusCode());
                } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                        return response()->json(['token_invalid'], $e->getStatusCode());
                } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                        return response()->json(['token_absent'], $e->getStatusCode());
                }
            return response()->json(compact('user'));
        }

        public function delete($id)
        {
            if (JWTAuth::parseToken()->authenticate()->id == User::find($id)->id) {
                $res = DB::table('token')
                    ->where('user_id', $id)
                    ->delete();

                $user = User::find($id);
                $user->delete();
                    return response()->json("Deleted", 201);
            } else
                    return response()->json("Unauthorized");
        }

        public function update(Request $request, $id)
        {
                $validator = Validator::make($request->all(), [
                        'username' => 'string|regex:/[a-zA-Z0-9_-]/|max:255|unique:users',
                        'email' => 'string|email|max:255|unique:users',
                        'pseudo' => 'max:255',
                        'password' => 'string|confirmed',
                    ]);

                if($validator->fails()){
                        return response()->json($validator->errors()->toJson(), 400);
                }

                if (JWTAuth::parseToken()->authenticate()->id == User::find($id)->id) {
                        $data = User::find($id);
                        if($data) {
                                if ($request->get('username'))
                                        $data->username = $request->get('username');
                                if ($request->get('email'))
                                        $data->email = $request->get('email');
                                if ($request->get('pseudo'))
                                        $data->pseudo = $request->get('pseudo');
                                if ($request->get('password'))
                                        $data->password = $request->get('password');
                        $data->save();
                        $message = "Ok";
                        return response()->json(compact('message', 'data'), 201);
                        }
                } else
                return response()->json("Unauthorized");
        }

        public function getUsers(Request $request)
        {
              $offset = 0;
              $limit = 5;
              $message = "Ok";
              $validator = Validator::make($request->all(), [
                  'username' => 'string|regex:/[a-zA-Z0-9_-]/|max:255|',
                  'page' => 'numeric',
                  'perPage' => 'numeric',
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

              if ($request->get('username')) {
                  $data = DB::table('users')
                      ->where('username', 'like', $request->get('username') . '%')
                      ->orderBy('id', 'DESC')
                      ->offset($offset)
                      ->limit($limit)
                      ->get();
                  return response()->json(compact('message', 'data', 'pager'), 200);
              } else {
                  $data = DB::table('users')
                      ->orderBy('id', 'DESC')
                      ->offset($offset)
                      ->limit($limit)
                      ->get();
                  return response()->json(compact('message', 'data', 'pager'), 200);
              }
        }

        public function getUser($id)
        {
                  $message = "Ok";
                  $data = User::find($id);
                  return response()->json(compact('message', 'data'), 200);
        }
    }
