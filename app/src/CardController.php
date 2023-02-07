<?php

namespace {

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Core\Environment;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\FieldType\DBField;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

    class CardController extends PageController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [
            'view',
            'edit',
            'payments',
            'createcard',
            'wipe',
            'CreateCardForm'
        ];

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }

        public function view(){
            $card_id = $this->request->Param('ID');
            $card = cards::get()->find('card_id', $card_id);

            return $this->customise(['Card'=>$card])->renderWith(['CardPage', 'Page']);
        }

        public function payments(){
            $card_id = $this->request->Param('ID');
            $card = cards::get()->find('card_id', $card_id);

            return $this->customise(['Card'=>$card])->renderWith(['CardPayments', 'Page']);
        }

        public function edit(){
            $card_id = $this->request->Param('ID');
            $card = cards::get()->find('card_id', $card_id);

            $fields = singleton('cards')->getFrontEndFields();
            $actions = FieldList::create(
                FormAction::create('save', 'Save')
            );
            $form = Form::create($this, 'edit', $fields, $actions);
            return $form;
        }

        public function save() {
            
        }

        public function createcard() {
            return $this->customise([
                'Form' => $this->CreateCardForm()
            ])->renderWith('Page');
        }

        public function CreateCardForm() {
            $fields = new FieldList(
                TextField::create('card_name', 'Card name'),
                ToggleCompositeField::create('Advanced', 'Advanced', [
                    CheckboxField::create('enable', 'Enable'),
                    NumericField::create('tx_max', 'Max transaction (sats)'),
                    NumericField::create('day_max', 'Daily max (sats)'),
                    //@TODO: NEED TO WARN PEOPLE WAYING THIS IS UNREVERSABLE 
                    CheckboxField::create('uid_privacy', 'UID Privacy'),
                    CheckboxField::create('allow_neg_bal', 'Allow negative balance')
                ])
            );

            $defaultVals = [
                'card_name' => 'Your card',
                'enable' => true,
                'tx_max' => '10000',
                'day_max' => '20000',
                'uid_privacy' => true,
                'allow_neg_bal' => false
            ];

            $fields->setValues($defaultVals);

            $actions = new FieldList(
                FormAction::create('doCreateCard')->setTitle('Create')
            );

            $required = new RequiredFields('card_name', 'tx_max', 'day_max');

            $form = new Form($this, 'CreateCardForm', $fields, $actions, $required);

            return $form;
        }

        public function doCreateCard($data, $form) {
            // curl 'localhost:9001/createboltcard?card_name=card_5&enable=true&tx_max=1000&day_max=10000&uid_privacy=true&allow_neg_bal=true'

            //validation
            if($card_exists = cards::get()->find('card_name', $data['card_name'])) {
                $form->sessionMessage('Card name '.$data['card_name'].' exists');
                return $this->redirectBack();
            }


            $boolean_values = [
                'enable', 'uid_privacy', 'allow_neg_bal'
            ];
            foreach($data as $key => $val ) {
                if(in_array($key, $boolean_values)) {
                    $data[$key] = $val ? 'true' : 'false';
                }
                if(filter_var($val, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND)) {
                    $data[$key] = filter_var($val, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
                }
            }

            $whitelist = [
                'card_name',
                'enable',
                'tx_max',
                'day_max',
                'uid_privacy',
                'allow_neg_bal'
            ];
            $query = array_intersect_key($data, array_flip($whitelist));
            $boltcard_hostname = Environment::getEnv('SS_BOLTCARD_HOST');

            //@TODO: make it able to set the container name?
            $res = (new GuzzleHttp\Client())->request('GET', 'boltcard_main:9001/createboltcard', [
                'query' => $query
            ]);

            $response = json_decode($res->getBody());
            //IF SUCCESS
            //{"status": "OK", "url": "...."}
            if($response->status == 'OK') {
                $newCard = cards::get()->find('card_name', $data['card_name']);
                if(!$newCard) {
                    $form->sessionMessage('There was a problem creating a new card');
                    return $this->redirectBack();
                }
                $url = $response->url;
                $options = new QROptions([
                    'scale' => 20,
                    'imageTransparent' => false
                ]);
                $qrcode = (new QRCode($options))->render($url);

                return $this->customise([
                    'Content' => DBField::create_field('HTMLText', '<p>Scan the QR code below.</p><p>'.$url.'</p><img src="'.$qrcode.'" alt="QR Code" width="500" height="500" />')
                ])->renderWith('Page');
            } else if($response->status == 'ERROR') {
                $form->sessionMessage('There was an api error: '.$response->reason);
                return $this->redirectBack();
            }
        }

        public function wipe() {
            die('not implemeted');
        }
    }
}

