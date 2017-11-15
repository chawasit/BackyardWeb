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

    public function historyView($from, $to) {
        try {
            $from = Carbon::createFromFormat('Y-m-d', $from);
            $to = Carbon::createFromFormat('Y-m-d', $to)->addDays(1);
        } catch(Exception $e) {
            abort(400);
        }
        $logs = Log::where('created_at', '>', $from)->where('created_at', '<', $to)
            ->orderBy('created_at', 'desc')->get();
        $notifications = Log::where('created_at', '>', $from)->where('created_at', '<', $to)
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
                $maxHumidity = $log->temperature;
            if($log->humidity < $lowHumidity)
                $lowHumidity = $log->temperature;
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
            $to = Carbon::createFromFormat('Y-m-d', $to)->addDays(1);
        } catch(Exception $e) {
            abort(400);
        }
        $logs = Log::where('created_at', '>', $from)->where('created_at', '<', $to)
        ->orderBy('created_at', 'desc')->get();

        return $logs;
    }

    public function notification() {
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();

        return $notifications;
    }
}
