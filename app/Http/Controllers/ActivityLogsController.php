<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $Activity_logs = ActivityLogs::select('activity_logs.*')
            ->join(DB::raw('(SELECT pseudo, MAX(date_activity) AS latest FROM activity_logs GROUP BY pseudo) AS latest_logs'), function ($join) {
                $join->on('activity_logs.pseudo', '=', 'latest_logs.pseudo')
                    ->on('activity_logs.date_activity', '=', 'latest_logs.latest');
            })
            ->orderBy('activity_logs.date_activity', 'desc')
            ->get()
            ->map(function ($log) {
                if (isset($log->controller)) {
                    $log->controller = ucfirst(preg_replace('/Controller$/', '', $log->controller));
                }
                return $log;
            });
        // dd($request);

        return response()->json($Activity_logs);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $pseudo =  $user ? $user->pseudo : 'inconnu';
        $userAgent = $_SERVER['REMOTE_ADDR'];
        $route = $request->input('route');
        $controller = $request->input('controller') ?? 'inconnu';
        $action = $request->input('action') ?? 'inconnu';
        $evenement = ($user?->name ?? 'inconnu') . ' ' . ($user?->last_name ?? 'inconnu'). ' a consulté ' . $route;
        $date_activity = Carbon::now();
        $ip = $request->ip();
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        // var_dump($hostname);
        \Log::info("Visite page principale par $pseudo : $controller@$action (route: $route) $evenement $date_activity, $userAgent");
       $DataAct= ActivityLogs::create(
            [
                'pseudo'=>$pseudo,
                'controller' => $controller,
                'action' => $action,
                'evenement'=>$evenement,
                'ip_address'=>$ip,
                'date_activity'=>$date_activity,
                'hostname'=>$hostname,

            ]
        );
        return response()->json(['status' => 'logged']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityLogs $activityLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityLogs $activityLogs)
    {
        //
    }
}
