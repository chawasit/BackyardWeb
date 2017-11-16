<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Log;
use App\Notification;

class MonitorController extends Controller
{
    public function dashboard() {
        $today = Carbon::today();
        $nextDay = $today->tomorrow();
        $logs = Log::where('created_at', '>', $today)
            ->where('created_at', '<', $nextDay)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard', ['logs' => $logs, 'notifications' => $notifications]);
    }

    public function logLatest() {
        $logs = Log::orderBy('created_at', 'desc')
            ->take(60)
            ->get();
        $logs = array_reverse($logs->toArray());
        return $logs;
    }

    public function history() {
        return view('history');
    }

    public function historyRedirect(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        return redirect("/history/${from}/${to}");
    }

    public function historyView(Request $request) {
        try {
            $from = Carbon::createFromFormat('m/d/Y g:i a', $request->input('from'));
            $to = Carbon::createFromFormat('m/d/Y g:i a', $request->input('to'));
        } catch(Exception $e) {
            abort(400);
        }
        $logs = Log::where('created_at', '>', $from)->where('created_at', '<', $to)
            ->orderBy('created_at', 'asc')->get();
        $notifications = Notification::where('created_at', '>', $from)->where('created_at', '<', $to)
            ->orderBy('created_at', 'desc')->get();

        $maxTemp = 0;
        $lowTemp = 100;

        $maxHumidity = 0;
        $lowHumidity = 100;

        foreach($logs as $log) {
            if($log->temperature > $maxTemp)
                $maxTemp = $log->temperature;
            if($log->temperature < $lowTemp)
                $lowTemp = $log->temperature;

            if($log->humidity > $maxHumidity)
                $maxHumidity = $log->humidity;
            if($log->humidity < $lowHumidity)
                $lowHumidity = $log->humidity;
        }

        return view('history', ['data' => [
            'logs' => $logs, 
            'notifications' => $notifications
        ],
            'from' => $from,
            'to' => $to,
            'lowTemp' => $lowTemp,
            'maxTemp' => $maxTemp,
            'lowHumidity' => $lowHumidity,
            'maxHumidity' => $maxHumidity
        ]);
    }

    public function log($from, $to) {
        try {
            $from = Carbon::createFromFormat('Y-m-d', $from);
            $from->hour = 0;
            $from->minute = 0;
            $from->second = 0;
            $to = Carbon::createFromFormat('Y-m-d', $to)->addDays(1);
            $to->hour = 0;
            $to->minute = 0;
            $to->second = 0;
        } catch(Exception $e) {
            abort(400);
        }
        $logs = Log::where('created_at', '>', $from)->where('created_at', '<', $to)
        ->orderBy('created_at', 'desc')->get();

        $logs = array_reverse($logs->toArray());
        return $logs;
    }

    public function notification() {
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();

        return $notifications;
    }
}
