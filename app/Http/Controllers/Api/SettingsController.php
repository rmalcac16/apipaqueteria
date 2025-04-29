<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Show one or more settings.
     *
     * @param string|null $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(?string $key = null)
    {
        if ($key !== null) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return response()->json(['message' => 'Setting not found.'], 404);
            }

            return response()->json([
                'key' => $setting->key,
                'value' => $setting->value,
            ]);
        }

        // Retornar todos los settings
        $settings = Setting::all()->pluck('value', 'key');

        return response()->json($settings);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update one or more settings.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ?string $key = null)
    {
        $data = $request->all();

        if ($key !== null) {
            // Modo individual
            $request->validate([
                'value' => 'required|string',
            ]);

            $setting = Setting::firstOrNew(['key' => $key]);
            $setting->value = $request->input('value');
            $setting->save();

            return response()->json([
                'message' => 'Configuración actualizada correctamente.',
                'key' => $setting->key,
                'value' => $setting->value,
            ]);
        }

        // Modo múltiple
        $updated = [];

        foreach ($data as $settingKey => $value) {
            $setting = Setting::firstOrNew(['key' => $settingKey]);
            $setting->value = $value;
            $setting->save();

            $updated[] = [
                'key' => $setting->key,
                'value' => $setting->value,
            ];
        }

        return response()->json([
            'message' => 'Configuraciones actualizadas correctamente.',
            'updated' => $updated,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
