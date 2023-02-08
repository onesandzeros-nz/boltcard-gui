<?php

namespace {

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;

    class CardEditController extends PageController
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
            'index',
            'Form',
            'save',
        ];

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }

        public function index(HTTPRequest $request){
            $card_id = $request->Param('ID');
            $card = cards::get()->find('card_id', $card_id);

            return $this->customise(['Card'=>$card])->renderWith(['Page']);
        }

        public function Form(){
            $card_id = $this->request->Param('ID');
            $card = cards::get()->find('card_id', $card_id);

            $fields = singleton('cards')->getFrontEndFields();
            $actions = FieldList::create(
                FormAction::create('save', 'Save')
            );
            $form = Form::create($this, 'Form', $fields, $actions);

            $fields->setValues($card ? $card->toMap() : []);
            return $form;
        }

        public function save($data, $form) {
            die(print_r($data,true));
        }

    }
}
