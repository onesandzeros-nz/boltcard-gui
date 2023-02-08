<?php

use SilverStripe\ORM\DataObject;

class settings extends DataObject {
	
	private static $db = [
		'setting_id' => 'Int',//                       |           | not null | generated always as identity
		'name' => 'Varchar(30)',//                  | Varchar varying(30)      |           | not null | ''::Varchar varying
		'value' => 'Varchar(128)'//                  | Varchar varying(128)      |           | not null | ''::Varchar varying
	];

	private static $summary_fields = [
		'setting_id',
		'name',
		'value'
	];
}