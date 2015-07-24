<?php

class CategoriesController extends \BaseController {

	/**
	 * Display a listing of categories
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories = Category::all();

		return View::make('categories.index', compact('categories'));
	}

	/**
	 * Show the form for creating a new category
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('categories.create');
	}

	/**
	 * Store a newly created category in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$categoryValidator = Validator::make($data = Input::all(), Category::$rules);

		if ($categoryValidator->fails())
		{
			return Redirect::back()->withErrors($categoryValidator)->withInput();
		}


        /* Category */
        if (Input::has('createCategory')) {
            Category::create($data);
            $message = "登録しました。";
        }

        if (Input::has('deleteCategory')) {
            $c = Category::where('Bumon', Input::get('Bumon'))->first();
            Category::destroy($c->id);
            $message = "削除しました。";
        }

        if (Input::has('updateCategory')) {

            $err = array(
                'required' => '新しい部門名を入力してください。',
            );

            $categoryValidator = Validator::make($data = Input::all(), Category::$update_rules, $err);
            if ($categoryValidator->fails()) {
                return Redirect::back()->withErrors($categoryValidator)->withInput();
            }

            $c = Category::where('Bumon', Input::get('Bumon'))->first();
            Category::destroy($c->id);
            $data['Bumon'] = $data['new_categoryName'];
            Category::create($data);
            $message = "更新しました。";
        }


        return Redirect::route('employees.index')->with('message',$message);

	}

	/**
	 * Display the specified category.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$category = Category::findOrFail($id);

		return View::make('categories.show', compact('category'));
	}

	/**
	 * Show the form for editing the specified category.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$category = Category::find($id);

		return View::make('categories.edit', compact('category'));
	}

	/**
	 * Update the specified category in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$category = Category::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Category::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$category->update($data);

		return Redirect::route('categories.index');
	}

	/**
	 * Remove the specified category from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Category::destroy($id);

		return Redirect::route('categories.index');
	}

}
