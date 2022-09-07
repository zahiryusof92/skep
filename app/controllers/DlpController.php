<?php

use Helper\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DlpController extends \BaseController
{

	public function deposit()
	{
		$this->checkAvailableAccess();

		if (Auth::user()->getFile) {
			$model = DlpDeposit::where('file_id', Auth::user()->getFile->id)->first();

			$viewData = array(
				'title' => trans('app.menus.dlp.deposit'),
				'panel_nav_active' => 'dlp_panel',
				'main_nav_active' => 'dlp_main',
				'sub_nav_active' => 'dlp_deposit',
				'image' => '',
				'model' => (!empty($model) ? $model : null),
			);

			return View::make('dlp.deposit', $viewData);
		}

		App::abort(404);
	}

	public function fileUpload()
	{
		$this->checkAvailableAccess();

		if (Request::ajax()) {
			$files = Request::file();

			foreach ($files as $file) {
				$destinationPath = Config::get('constant.file_directory.dlp_deposit');
				$filename = date('YmdHis') . "_" . $file->getClientOriginalName();
				$upload = $file->move($destinationPath, $filename);

				if ($upload) {
					return Response::json(['success' => true, 'file' => $destinationPath . "/" . $filename, 'filename' => $filename]);
				}
			}
		}

		return Response::json(['error' => true, 'message' => "Fail"]);
	}

	public function storeDeposit()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'amount' => 'required|numeric',
				'maturity_date' => 'required|date',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
				if (Auth::user()->getFile) {
					$created = DlpDeposit::create([
						'file_id' => Auth::user()->getFile->id,
						'amount' => $request['amount'],
						'maturity_date' => $request['maturity_date'],
						'status' => DlpDeposit::PENDING,
					]);

					if ($created) {
						$module = Str::upper($this->getModule());
						$remarks = $module . ': Deposit has been submitted.';
						$this->addAudit($created->file_id, $module, $remarks);

						return Response::json([
							'success' => true,
							'message' => trans('app.successes.saved_successfully')
						]);
					}
				}
			}
		}

		return Response::json([
			'error' => true,
			'message' => trans('app.errors.occurred')
		]);
	}

	public function progress()
	{
		$this->checkAvailableAccess();

		$viewData = array(
			'title' => trans('app.menus.dlp.progress'),
			'panel_nav_active' => 'dlp_panel',
			'main_nav_active' => 'dlp_main',
			'sub_nav_active' => 'dlp_progress',
			'image' => ''
		);

		return View::make('dlp.progress', $viewData);
	}

	public function storeProgress()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'date' => 'required|date',
				'percentage' => 'required|numeric',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
			}
		}
	}

	public function period()
	{
		$this->checkAvailableAccess();

		$viewData = array(
			'title' => trans('app.menus.dlp.period'),
			'panel_nav_active' => 'dlp_panel',
			'main_nav_active' => 'dlp_main',
			'sub_nav_active' => 'dlp_period',
			'image' => ''
		);

		return View::make('dlp.period', $viewData);
	}

	public function storePeriod()
	{
		if (Request::ajax()) {
			$request = Request::all();

			$rules = [
				'duration' => 'required',
			];

			$validator = Validator::make($request, $rules);
			if ($validator->fails()) {
				return Response::json([
					'error' => true,
					'errors' => $validator->errors(),
					'message' => trans('Validation Fail')
				]);
			} else {
			}
		}
	}

	private function encodeID($id)
	{
		return Helper::encode($id);
	}

	private function decodeID($id)
	{
		return Helper::decode($id);
	}

	private function getModule()
	{
		return 'Defect Liability Period';
	}

	private function checkAvailableAccess()
	{
		if ((!Auth::user()->getAdmin() && !Auth::user()->isCOB()) && !Auth::user()->isDeveloper()) {
			App::abort(404);
		}
	}
}
