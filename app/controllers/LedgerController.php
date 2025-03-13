<?php

use Helper\OdesiLife;
use Illuminate\Support\Facades\Redirect;

class LedgerController extends \BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->checkAvailableAccess();

		$user = User::find(Auth::user()->id);
		if ($user) {
			$council = Company::find($user->getCOB->id);
			if ($council) {
				$file = Files::find($user->getFile->id);
				if ($file) {
					$data = [
						'first_name' => $this->firstName($user->full_name),
						'last_name' => $this->lastName($user->full_name),
						'username' => $user->username,
						'council' => [
							'state_code' => $council->states->code,
							'name' => $council->name,
							'code' => $council->short_name
						],
						'building' => [
							'file_no' => $file->file_no,
							'name' => ($file->strata ? $file->strata->name : null)
						]
					];

					/**
					 * Sync Owners
					 */
					if ($file->owner) {
						$total = Buyer::where('file_id', $file->id)->count();
						$per_page = 1;
						$total_page = ceil($total / $per_page);

						for ($x = 0; $x < $total_page; $x += $per_page) {
							$data_owners = [];
							$owners = Buyer::where('file_id', $file->id)->skip($x)->take($per_page)->get();
							if ($owners) {
								$users = [];
								foreach ($owners as $owner) {
									array_push($users, [
										'building_file_no' => $file->file_no,
										'unit_no' => $owner->unit_no,
										'first_name' => $this->firstName($owner->owner_name),
										'last_name' => $this->lastName($owner->owner_name),
										'email' => $owner->email,
										'username' => $owner->id,
									]);
								}

								$data_owners['users'] = $users;
								if (!empty($data_owners)) {
									$response_owners = (new OdesiLife())->owners($data_owners);

									// echo '<pre>' . print_r($response_owners, true) . '</pre>';
								}
							}
						}
					}

					/**
					 * Login to Ledger
					 */
					$response = (new OdesiLife())->login($data);
					if (empty($response->success) == false && $response->success == true) {
						if (!empty($response->data) && !empty($response->data->url)) {
							return Redirect::to($response->data->url);
						}
					}
				}
			}
		}

		App::abort(404);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	private function firstName($name)
	{
		if (str_word_count($name) > 1) {
			$first_name = substr($name, 0, strrpos($name, ' '));

			return $first_name;
		}

		return $name;
	}

	private function lastName($name)
	{
		if (str_word_count($name) > 1) {
			$last = explode(' ', $name);
			$last_name = array_pop($last);

			return $last_name;
		}

		return $name;
	}

	private function checkAvailableAccess()
	{
		if (!AccessGroup::hasAccessModule('Ledger')) {
			App::abort(404);
		}

		if (!Auth::user()->isJMB() && !Auth::user()->isMC() && !Auth::user()->isDeveloper()) {
			App::abort(404);
		}
	}
}
