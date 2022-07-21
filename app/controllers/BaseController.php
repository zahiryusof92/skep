<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

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
        $this->module = Config::get('constant.module');  
    }

    /**
     * Create Log.
     * @param int $file_id
     * @param string $module. $remarks
     * @return void
     */
    protected function addAudit($file_id = 0, $module, $remarks) {
        # Audit Trail        
        $agent = [
            'ip' => Request::ip(),
            'browser' => Request::server('HTTP_USER_AGENT'),
            'url' => Request::fullUrl(),
        ];
        if($file_id == 0 || empty($file_id)) {
            $file_id = 0;
        }
        $auditTrail = AuditTrail::create([
            'file_id' => $file_id,
            'company_id' => !empty(Session::get('admin_cob'))? Session::get('admin_cob') : Auth::user()->company_id,
            'module' => $module,
            'remarks' => is_array($remarks)? json_encode($remarks) : $remarks,
            'agent' => json_encode($agent),
            'audit_by' => Auth::user()->id,
        ]);   
    }

    /**
     * Check valid secret
     * @return void
     */
    protected function validateSecret() {
		$client = APIClient::where('secret', Request::header('secret'))
                            ->where('expiry', '>=', Carbon::now()->startOfDay())
                            ->where('status', true)
                            ->first();
        if($client) {
            return [
                'success' => true,
                'client' => $client
            ];
        }
        return [
            'error' => true,
            'message' => 'Invalid Secret'
        ];
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
