<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;

class SecondSensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $firebaseUrl = 'https://tes-iot-9e4d4-default-rtdb.asia-southeast1.firebasedatabase.app/sensor2.json';
        $client = new Client();

        try {
            // Fetch data dari Firebase
            $response = $client->get($firebaseUrl, ['verify' => false]);
            $data = json_decode($response->getBody(), true);
    
            $sensors = [];
            $history = [];
            $latestTemperature = null;
            $condition = null;
            $description = null;
            $latestTimestamp = null;
            if (is_array($data)) {
                uasort($data, function($a, $b) {
                    return strtotime($a['timestamp'] ?? '1970-01-01') <=> strtotime($b['timestamp'] ?? '1970-01-01');
                });
            
                foreach ($data as $key => $value) {
                    $temperature = $value['temperature_celsius'] ?? null;
                    $timestamp = $value['timestamp'] ?? null;
            
                    $condition = null;
                    if ($temperature !== null) {
                        if ($temperature < 28) {
                            $condition = 'Dingin';
                        } elseif ($temperature < 31) {
                            $condition = 'Normal';
                        } else {
                            $condition = 'Panas';
                        }
                    }
            
                    $history[] = [
                        'time' => $timestamp,
                        'temperature' => $temperature,
                        'condition' => $condition,
                    ];
                }
            
                // Ambil data terbaru dari history
                if (!empty($history)) {
                    $latestRecord = end($history);
                    $latestTemperature = $latestRecord['temperature'] ?? null;
                    $latestTimestamp = $latestRecord['time'] ?? null;
            
                    if ($latestTemperature !== null) {
                        if ($latestTemperature < 28) {
                            $condition = 'Dingin';
                            $description = 'Dingin';
                        } elseif ($latestTemperature < 31) {
                            $condition = 'Normal';
                            $description = 'Normal';
                        } else {
                            $condition = 'Panas';
                            $description = 'Panas';
                        }
                    }
                }
            }
            
            
    
            return view('secondsensor.index', compact('user', 'history', 'latestTemperature', 'condition', 'description', 'latestTimestamp'));
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['error' => $e->getMessage()]);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
