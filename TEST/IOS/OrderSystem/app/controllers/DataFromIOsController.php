<?php

class DataFromIOsController extends \BaseController {

	/**
	 * Display a listing of datafromios
	 *
	 * @return Response
	 */
	public function index()
	{
		$datafromios = Datafromio::all();

		return View::make('datafromios.index', compact('datafromios'));
	}

	/**
	 * Show the form for creating a new datafromio
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('datafromios.create');
	}

	/**
	 * Store a newly created datafromio in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Datafromio::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Datafromio::create($data);

		return Redirect::route('dataFromIOs.index');
	}

	/**
	 * Display the specified datafromio.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$datafromio = Datafromio::findOrFail($id);

		return View::make('datafromios.show', compact('datafromio'));
	}

	/**
	 * Show the form for editing the specified datafromio.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$datafromio = Datafromio::find($id);

		return View::make('datafromios.edit', compact('datafromio'));
	}

	/**
	 * Update the specified datafromio in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$datafromio = Datafromio::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Datafromio::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$datafromio->update($data);

		return Redirect::route('dataFromIOs.index');
	}

	/**
	 * Remove the specified datafromio from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $id = Input::get('id');
		Datafromio::destroy($id);

        $message = "削除しました。";
		return Redirect::route('dataFromIOs.index')->with('message', $message);
	}

    public function clear()
    {
        Datafromio::truncate();
        return Redirect::route('dataFromIOs.index');
    }

}
