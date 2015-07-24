<?php

class SettingsController extends \BaseController
{

    /**
     * Display a listing of settings
     *
     * @return Response
     */
    public function index()
    {
        $settings = Setting::all();
        $shopsettings = Shopsetting::all();
        $receiptsettings = Receiptsetting::all();

        return View::make('settings.index', compact('settings', 'shopsettings', 'receiptsettings'));
    }

    /**
     * Show the form for creating a new setting
     *
     * @return Response
     */
    public function create()
    {
        return View::make('settings.create');
    }

    /**
     * Store a newly created setting in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Setting::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Setting::create($data);

        return Redirect::route('settings.index');
    }

    /**
     * Display the specified setting.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);

        return View::make('settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified setting.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $setting = Setting::find($id);

        return View::make('settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $id = Input::get('id');
        $setting = Setting::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Setting::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        if (Input::has('modeSetting')) {
            $setting->update($data);
        }
        $message = "更新しました。";
        return Redirect::route('settings.index')->with('message',$message);
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Setting::destroy($id);

        return Redirect::route('settings.index');
    }

}
