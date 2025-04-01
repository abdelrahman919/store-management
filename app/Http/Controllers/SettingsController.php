<?php

namespace App\Http\Controllers;

use App\Models\settings;
use App\Http\Requests\UpdatesettingsRequest;
use App\Services\SettingsService;

class SettingsController extends BaseController
{
    private SettingsService $settingService;

    public function __construct(SettingsService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success($this->settingService->getAllValues());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $key)
    {
        $value = $this->settingService->getValue($key);
        return $this->success([$key => $value]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesettingsRequest $request)
    {
        $validated = $request->validated();
        $key = $validated['key'];
        $value = $validated['value'];
        $this->settingService->setValue($key, $value);
        return $this->success([$key => $this->settingService->getValue($key)]);
    }

}
