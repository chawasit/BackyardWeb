<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Log;
use App\Notification;

class LogController extends Controller
{
    public function createValue(Request $request) {
        $validator = Validator::make($request->all(), [
            'temperature' => 'required|max:255',
            'humidity' => 'required',
            'pump' => 'required'
        ]);

        if (!$validator->fails()) {
            $log = new Log();
            $log->temperature = (int) $request->input('temperature');
            $log->humidity = (int) $request->input('humidity');
            $log->pump = (bool) $request->input('pump');
            $log->save();

            return "True";
        } else {
            abort(400);
            return "False";
        }
    }

    public function createNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'message' => 'required|max:255',
        ]);

        if (!$validator->fails()) {
            $notification = new Notification();
            $notification->message = $request->input('message');
            $notification->save();

            return "True";
        } else {
            abort(400);
            return "False";
        }
    }

}
