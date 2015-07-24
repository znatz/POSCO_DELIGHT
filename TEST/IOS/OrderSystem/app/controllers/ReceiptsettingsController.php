<?php

class ReceiptsettingsController extends \BaseController {

	/**
	 * Display a listing of receiptsettings
	 *
	 * @return Response
	 */
	public function index()
	{
		$receiptsettings = Receiptsetting::all();

		return View::make('receiptsettings.index', compact('receiptsettings'));
	}

	/**
	 * Show the form for creating a new receiptsetting
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('receiptsettings.create');
	}

	/**
	 * Store a newly created receiptsetting in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Receiptsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Receiptsetting::create($data);

		return Redirect::route('receiptsettings.index');
	}

	/**
	 * Display the specified receiptsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$receiptsetting = Receiptsetting::findOrFail($id);

		return View::make('receiptsettings.show', compact('receiptsetting'));
	}

	/**
	 * Show the form for editing the specified receiptsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$receiptsetting = Receiptsetting::find($id);

		return View::make('receiptsettings.edit', compact('receiptsetting'));
	}

	/**
	 * Update the specified receiptsetting in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $id = Input::get('receiptsettingid');
		$receiptsetting = Receiptsetting::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Receiptsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$receiptsetting->update($data);
        $message = "更新しました。";

        return Redirect::route('settings.index')->with('message',$message);
	}

	/**
	 * Remove the specified receiptsetting from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Receiptsetting::destroy($id);

		return Redirect::route('receiptsettings.index');
	}

}
