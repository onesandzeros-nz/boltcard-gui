<?php

use SilverStripe\Assets\Image;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\TagField\TagField;
use SilverStripe\View\Parsers\URLSegmentFilter;

class cards extends DataObject {
	
	private static $db = [
		"card_id" => "Int",//                       |           | not null | generated always as identity
		"k0_auth_key" => "Varchar(32)",//                | Varchar(32)               |           | not null | 
		"k2_cmac_key" => "Varchar(32)",//                | Varchar(32)               |           | not null | 
		"k3" => "Varchar(32)",//                         | Varchar(32)               |           | not null | 
		"k4" => "Varchar(32)",//                         | Varchar(32)               |           | not null | 
		"uid"  => "Varchar(14)",//                       | Varchar varying(14)       |           | not null | ''::Varchar varying
		"last_counter_value" => "Int",// | Integer                     |           | not null | 
		"lnurlw_request_timeout_sec" => "Int",// | Integer                     |           | not null | 
		"lnurlw_enable" => "Varchar(1)",//              | Varchar(1)                |           | not null | 'N'::bpchar
		"tx_limit_sats" => "Int",//              | Integer                     |           | not null | 
		"day_limit_sats" => "Int",//             | integer                     |           | not null | 
		"lnurlp_enable" => "Varchar(1)",//              | Varchar(1)                |           | not null | 'N'::bpchar
		"card_name" => "Varchar(100)",//                  | Varchar varying(100)      |           | not null | ''::Varchar varying
		"email_address" => "Varchar(100)",//              | Varchar varying(100)      |           |          | ''::Varchar varying
		"email_enable" => "Varchar(1)",//               | Varchar(1)                |           | not null | 'N'::bpchar
		"uid_privacy" => "Varchar(1)",//                | Varchar(1)                |           | not null | 'N'::bpchar
		"one_time_code" => "Varchar(32)",//               | Varchar(32)               |           | not null | ''::bpchar
		"one_time_code_expiry" => "Datetime",//        | timestamp with time zone    |           |          | now() + '1 day'::interval
		"one_time_code_used" => "Varchar(1)",//         | Varchar(1)                |           | not null | 'Y'::bpchar
		"allow_negative_balance" => "Varchar(1)",//     | Varchar(1)                |           | not null | 'N'::bpchar
		"wiped" => "Varchar(1)",//                      | Varchar(1)                |           | not null | 'N'::bpchar
	];

	private static $has_one = [

	];

	private static $has_many = [

	];

    private static $many_many = [

    ];

	private static $summary_fields = [
		"card_name",
		"card_id",
		"tx_limit_sats",
		"day_limit_sats",
		"lnurlw_enable",
		"lnurlp_enable",
		"allow_negative_balance",
		"email_enable",
	];

	private static $owns = [

	];
    
    private static $indexes = [

    ];

    // private static $default_sort = 'Sort ASC';

    public function Payments() {
    	return card_payments::get()->filter('card_id', $this->card_id);
    }

}