<?php

use Helper\Helper;

class MPSSyncController extends \BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// if ((URL::to('/') == 'https://skep.lphs.gov.my' || URL::to('/') == 'https://selangor.ecob.my') || (URL::to('/') == 'https://test.odesi.tech' || URL::to('/') == 'https://skep.app')) {
			if (Auth::user()->getAdmin()) {
				$viewData = array(
					'title' => 'MPS Sync',
					'panel_nav_active' => 'cob_panel',
					'main_nav_active' => 'cob_main',
					'sub_nav_active' => 'mps_sync',
				);

				return View::make('mps_en.index', $viewData);
			}
		// }

		return $this->errorPage();
	}

	public function getFileList()
	{
		$file = FileSyncLog::join('files', 'file_sync_log.file_id', '=', 'files.id')
			->select(['file_sync_log.*', 'files.id as file_id', 'files.file_no as file_no'])
			->where('files.is_deleted', 0);

		return Datatables::of($file)
			->editColumn('file_no', function ($model) {
				return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file_id)) . "'>" . $model->file_no . "</a>";
			})
			->editColumn('status', function ($model) {
				return strtoupper($model->status);
			})
			->editColumn('created_at', function ($model) {
				return $model->created_at->format('d-m-Y H:i:s');
			})
			->make(true);
	}

	public function getFinanceList()
	{
		$file = FinanceSyncLog::join('files', 'finance_sync_log.file_id', '=', 'files.id')
			->join('finance_file', 'finance_sync_log.finance_file_id', '=', 'finance_file.id')
			->select(['finance_sync_log.*', 'files.id as file_id', 'files.file_no as file_no', 'finance_file.id as finance_id', 'finance_file.year as finance_year', 'finance_file.month as finance_month'])
			->where('files.is_deleted', 0)
			->where('finance_file.is_deleted', 0);

		return Datatables::of($file)
			->editColumn('file_no', function ($model) {
				return "<a style='text-decoration:underline;' href='" . URL::action('AdminController@house', Helper::encode($model->file_id)) . "'>" . $model->file_no . "</a>";
			})
			->addColumn('finance_file_no', function ($model) {
				if ($model->finance_month) {
					$dateObj = DateTime::createFromFormat('!m', $model->finance_month);
					$monthName = $dateObj->format('M'); // March

					$finance_month = strtoupper($monthName);
				} else {
					$finance_month = "<i>(not set)</i>";
				}

				return "<a style='text-decoration:underline;' href='" . URL::action('FinanceController@editFinanceFileList', Helper::encode($model->finance_id)) . "'>" . $model->file_no . " " . $model->finance_year . "-" . $finance_month . "</a>";
			})
			->editColumn('status', function ($model) {
				return strtoupper($model->status);
			})
			->editColumn('created_at', function ($model) {
				return $model->created_at->format('d-m-Y H:i:s');
			})
			->make(true);
	}

	public function destroy() {
		if (Request::ajax()) {
			FileSyncLog::truncate();
			FinanceSyncLog::truncate();

			return "true";
		}

		return "false";
	}

	public function errorPage()
	{
		$viewData = array(
			'title' => trans('app.errors.page_not_found'),
			'panel_nav_active' => '',
			'main_nav_active' => '',
			'sub_nav_active' => '',
			'image' => ""
		);

		return View::make('404_en', $viewData);
	}
}
