<?php

class Shopsetting extends \Eloquent
{

    public static $rules = [
        'tempo' => 'required',
        'reji' => 'required',
        'receipt' => 'required',
    ];

    protected $connection = 'sqlite2';
    protected $table = "ShopSettings";
    protected $fillable = ['tempo', 'reji', 'receipt'];
    public $timestamps = false;


}