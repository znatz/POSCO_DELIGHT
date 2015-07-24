<?php

class Setting extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
    protected $connection = 'sqlite2';
    protected $table = 'Settings';
    public $timestamps = false;
    protected $fillable = ['azmode', 'tantou', 'bumode', 'picmode', 'nimode'];

}