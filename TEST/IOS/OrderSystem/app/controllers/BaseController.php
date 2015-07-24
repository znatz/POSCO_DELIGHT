<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    protected $messageBag = null;

    public function __construct()
    {
        $this->messageBag = new Illuminate\Support\MessageBag();
    }
}
