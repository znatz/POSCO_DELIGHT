<?php

class Category extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		 'Bumon' => 'required'
	];

    public static $update_rules = [
        'new_categoryName' => 'required',
        'Bumon' => 'required',
    ];

	// Don't forget to fill this array
    protected $table = "BBUMAS";
	protected $fillable = ["Bumon"];
    public $timestamps = false;

}