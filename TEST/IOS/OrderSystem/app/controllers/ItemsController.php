<?php

class ItemsController extends \BaseController {

	/**
	 * Display a listing of items
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = Item::all();
        $categories = Category::all();
        $item = new Item();

		return View::make('items.index', compact('items', 'categories', 'item'));
	}

	/**
	 * Show the form for creating a new item
	 *
	 * @return Response
	 */
	public function create()
	{
        return Redirect::route('employees.index');
//        return View::make('items.create');
	}

	/**
	 * Store a newly created item in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        if (Input::get('selectedItem')) {
            $targetID = Input::get('selectedItem');
            $item = Item::find($targetID);

            $shops = Shop::all();
            $categories = Category::all();
            return View::make('items.index', compact('item', 'categories','shops'));
        }


		$validator = Validator::make($data = Input::all(), Item::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}


        /* Item */
        if (Input::has('createItem')) {

            $id = DB::table('BTSMAS')->count();
            $id++;
            $file = $id . '.png';

            if (move_uploaded_file($_FILES['upload']['tmp_name'], $file)) {
                echo $file;
            } else {
                echo "error";
            }

            $data['contents'] = file_get_contents($file);
            unlink($file);
            Item::create($data);
            $message = "登録しました。";
        }

        if (Input::has('deleteItem')) {
            $id = Input::get('idItem');
            Item::destroy($id);
            $message = "削除しました。";
        }

        if (Input::has('updateItem')) {
            $id = Input::get('idItem');
            $item = Item::find($id);
            $item->title = Input::get('title');
            $item->price = Input::get('price');
            $item->genka = Input::get('genka');
            $item->Bumon = Input::get('Bumon');
            $item->Kosu  = Input::get('Kosu');
            if (file_exists($_FILES['upload']['tmp_name']))
            {
                $file = $id . '.png';
                move_uploaded_file($_FILES['upload']['tmp_name'], $file);
                $item->contents = file_get_contents($file);
                unlink($file);
            }
            $item->save();
            $message = "更新しました。";
        }

        return Redirect::route('items.index')->with('message',$message);
	}

	/**
	 * Display the specified item.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$item = Item::findOrFail($id);

		return View::make('items.show', compact('item'));
	}

	/**
	 * Show the form for editing the specified item.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$item = Item::find($id);

		return View::make('items.edit', compact('item'));
	}

	/**
	 * Update the specified item in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$item = Item::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Item::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$item->update($data);

		return Redirect::route('items.index');
	}

	/**
	 * Remove the specified item from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Item::destroy($id);

		return Redirect::route('items.index');
	}

    public function showImg($id)
    {
        header('Content-Type:image/png');
        $item = Item::findorFail($id);
        echo $item->contents;
    }

    public function getItem($id)
    {
        $item = Item::findOrFail($id);
        dd("called");
        return Redirect::route('employees.index',compact('item'));
    }

}
