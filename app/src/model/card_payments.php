<?php

use SilverStripe\Assets\Image;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\TagField\TagField;
use SilverStripe\View\Parsers\URLSegmentFilter;

class card_payments extends DataObject {
	
	private static $db = [
		"card_payment_id" => "Int",//    | integer                  |           | not null | generated always as identity
		"card_id" => "Int",//             | integer                  |           | not null | 
		"lnurlw_k1" => "Varchar(32)",//           | character(32)            |           | not null | 
		"lnurlw_request_time" => "Datetime",// | timestamp with time zone |           | not null | 
		"ln_invoice" => "Varchar(1024)",//          | character varying(1024)  |           | not null | ''::character varying
		"amount_msats" => "BigInt",//        | bigint                   |           |          | 
		"paid_flag" => "Varchar(1)",//           | character(1)             |           | not null | 
		"payment_time" => "Datetime",//        | timestamp with time zone |           |          | 
		"payment_status" => "Varchar(100)",//       | character varying(100)   |           | not null | ''::character varying
		"failure_reason" => "Varchar(100)",//       | character varying(100)   |           | not null | ''::character varying
		"payment_status_time" => "Datetime"// | timestamp with time zone |           |          | 

	];

	private static $has_one = [

	];

	private static $has_many = [

	];

    private static $many_many = [

    ];

	private static $summary_fields = [
		'lnurlw_request_time',
		'payment_time',
		'payment_status',
		'failure_reason',
		'payment_status_time',
	];

	private static $owns = [

	];
    
    private static $indexes = [

    ];

    // private static $default_sort = 'Sort ASC';

}