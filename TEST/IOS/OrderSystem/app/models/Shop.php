<?php

class Shop extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		 'Tenpo' => 'required'
	];

    public static $update_rules = [
        'Tenpo' => 'required',
        'new_shopName' => 'required',
    ];
	// Don't forget to fill this array
    protected $table = "BMIMAS";
	protected $fillable = ["Tenpo"];
    public $timestamps = false;

}