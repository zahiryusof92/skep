<?php

use Illuminate\Support\Facades\Config;

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
        $this->eai_domain = Config::get('constant.eai.domain');
        $this->eai_route = Config::get('constant.eai.route');
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
