<?php

class BaseController extends Controller {

    public function __construct() {
        if (empty(Session::get('lang'))) {
            Session::put('lang', 'en');
        }

        if (empty(Session::get('admin_cob'))) {
            Session::put('admin_cob', '');
        }

        $locale = Session::get('lang');
        App::setLocale($locale);
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}
