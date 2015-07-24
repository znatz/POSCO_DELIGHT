<?php

class Employee extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		'name' => 'required'
	];

    public static $update_rules = [
        'name' => 'required',
        'new_name' => 'required'
    ];

	// Don't forget to fill this array
    protected  $table = "BTAMAS";
    protected $fillable = ["name"];
    public $timestamps = false;


}