<?php

namespace {

use SilverStripe\Assets\File;
use SilverStripe\Assets\Storage\AssetStore;
use SilverStripe\Assets\Upload;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FileField;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\MimeValidator\MimeUploadValidator;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\View\ArrayData;

	class SettingsController extends PageController {

		public function __construct($dataRecord = null)
	    {
	        parent::__construct($dataRecord);

	        $this->dataRecord->URLSegment = 'settings';
	    }

		private static $allowed_actions = [
			'edit',
			'EditForm'
		];

		public function index(HTTPRequest $request) {

			return $this->customise([

			])->renderWith(['SettingsPage', 'Page']);
		}

		public function Settings() {
			$settingsNames = [
				'LOG_LEVEL',
				'AES_DECRYPT_KEY',
				'HOST_DOMAIN',
				'MIN_WITHDRAW_SATS',
				'MAX_WITHDRAW_SATS',
				'LN_HOST',
				'LN_PORT',
				'LN_TLS_FILE',
				'LN_MACAROON_FILE',
				'FEE_LIMIT_SAT',
				'FEE_LIMIT_PERCENT',
				'LN_TESTNODE',
				'FUNCTION_LNURLW',
				'FUNCTION_LNURLP',
				'FUNCTION_EMAIL',
				'AWS_SES_ID',
				'AWS_SES_SECRET',
				'AWS_SES_EMAIL_FROM',
				'EMAIL_MAX_TXS',
				'FUNCTION_INTERNAL_API',
			];

			$settings = [];
			// echo '<pre>';

			foreach($settingsNames as $name) {
				if($setting = settings::get()->find('name', $name)) {
					// print_r($setting);
					$settings[] = new ArrayData([
						'name' => $setting->name,
						'value' => $setting->value
					]);
				}
			}

			return new ArrayList($settings);
		}

		public function edit() {
			return $this->customise([
				'Form' => $this->EditForm()
			])->renderWith(['Page', 'Page']);
		}

		public function EditForm() {
			$fields = new FieldList(
				DropdownField::create('LOG_LEVEL', 'LOG_LEVEL', ['DEBUG' => 'DEBUG', 'PRODUCTION' => 'PRODUCTION']),
				TextField::create('HOST_DOMAIN'),
				TextField::create('MIN_WITHDRAW_SATS')->setAttribute('pattern', '[0-9]+'),
				TextField::create('MAX_WITHDRAW_SATS')->setAttribute('pattern', '[0-9]+'),
				TextField::create('LN_HOST'),
				TextField::create('LN_PORT')->setAttribute('pattern', '[0-9]+'),
				$tlsUploadField = FileField::create('LN_TLS_FILE'),
				$macaroonUploadField = FileField::create('LN_MACAROON_FILE'),
				TextField::create('FEE_LIMIT_SAT')->setAttribute('pattern', '[0-9]+'),
				NumericField::create('FEE_LIMIT_PERCENT')->setScale(1)->setAttribute('min', 0)->setAttribute('max', 1),
				TextField::create('LN_TESTNODE'),
				DropdownField::create('FUNCTION_LNURLW', 'FUNCTION_LNURLW', ['ENABLE' => 'ENABLE', 'DISABLE' => 'DISABLE']),
				//@TODO: default to disable
				DropdownField::create('FUNCTION_LNURLP', 'FUNCTION_LNURLP', ['ENABLE' => 'ENABLE', 'DISABLE' => 'DISABLE']),
				// DropdownField::create('FUNCTION_EMAIL', 'FUNCTION_EMAIL', ['ENABLE' => 'ENABLE', 'DISABLE' => 'DISABLE'])
			);

			$tlsUploadField->setValidator(MimeUploadValidator::create())->setAllowedExtensions(['cert']);
			$macaroonUploadField->setValidator(MimeUploadValidator::create())->setAllowedExtensions(['macaroon']);

			$actions = new FieldList(
				FormAction::create('doSave')->setTitle('Save')
			);

			$settings = settings::get();
			foreach($settings as $setting) {
				if($field = $fields->dataFieldByName($setting->name)) {
					$field->setValue($setting->value);
				}
			}

			$required = new RequiredFields('LOG_LEVEL', 'HOST_DOMAIN');

			$form = new Form($this, 'EditForm', $fields, $actions, $required);

			return $form;

		}

		public function doSave($data, $form) {
			// die('<pre>'.print_r($data, true));
			foreach($data as $name => $val) {
				if($setting = settings::get()->find('name', $name)) {
					if(!is_array($val)) {
						//is not a file
						$setting->value = $val;
						$setting->write();
					} else {
						//@TODO: upload files to a specific path
						//make sure to protect the files
						if(isset($val['name']) && isset($val['tmp_name']) && $val['name'] && !empty($val['tmp_name'])) {
							$tmp_name = $val['tmp_name'];
							$type = $val['type'];

							$path = '/boltcard-files/';
							$filename = '';
							switch($name) {
								case 'LN_TLS_FILE':
									if($type != 'application/pkix-cert') {
										//validate the cert file MIME TYPE here just for LN_TLS_FILE
										//because PHP finfo function does not return correct MIME type for .cert files so SS is allowing 'text/plain' MIME type as well 
										$validationResult = new ValidationResult();
							            $validationResult->addFieldError('LN_TLS_FILE', 'File type does not match extension (.cert)');
							            $form->setSessionValidationResult($validationResult);
										$form->setSessionData($form->getData());
										return $this->redirectBack();
									}
									$filename = 'tls.cert';
									break;
								case 'LN_MACAROON_FILE':
									$filename = 'admin.macaroon';
									break;
							}

							$fp = fopen($path.$filename, 'w');
							$fcontent = file_get_contents($tmp_name);
							fwrite($fp, $fcontent);
							fclose($fp);


						}
					}
				}
			}
			return $this->redirect($this->Link());
		}

		public function certExists() {
			return file_exists('/boltcard-files/tls.cert');
		}

		public function macaroonExists() {
			return file_exists('/boltcard-files/admin.macaroon');
		}

		public function Title() {
			return 'Settings';
		}


	}

}