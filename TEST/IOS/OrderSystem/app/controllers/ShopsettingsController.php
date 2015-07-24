<?php

class ShopsettingsController extends \BaseController {

	/**
	 * Display a listing of shopsettings
	 *
	 * @return Response
	 */
	public function index()
	{
		$shopsettings = Shopsetting::all();

		return View::make('shopsettings.index', compact('shopsettings'));
	}

	/**
	 * Show the form for creating a new shopsetting
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('shopsettings.create');
	}

	/**
	 * Store a newly created shopsetting in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Shopsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Shopsetting::create($data);

		return Redirect::route('shopsettings.index');
	}

	/**
	 * Display the specified shopsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$shopsetting = Shopsetting::findOrFail($id);

		return View::make('shopsettings.show', compact('shopsetting'));
	}

	/**
	 * Show the form for editing the specified shopsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$shopsetting = Shopsetting::find($id);

		return View::make('shopsettings.edit', compact('shopsetting'));
	}

	/**
	 * Update the specified shopsetting in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $id = Input::get('shopid');
		$shopsetting = Shopsetting::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Shopsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$shopsetting->update($data);
        $message = "更新しました。";

        return Redirect::route('settings.index')->with('message',$message);
	}

	/**
	 * Remove the specified shopsetting from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Shopsetting::destroy($id);

		return Redirect::route('shopsettings.index');
	}

}
