<?php

class Receiptsetting extends \Eloquent
{

    public static $rules = [
        'tax'           => 'required',
        'haveReceipt'   => 'required',
        'haveStamp'     => 'required',
        'haveComment'   => 'required',
    ];

    protected $connection = 'sqlite2';
    protected $table = 'ReceiptSettings';
    protected $fillable = ['tax', 'haveReceipt', 'haveStamp', 'haveComment'];
    public $timestamps = false;

}