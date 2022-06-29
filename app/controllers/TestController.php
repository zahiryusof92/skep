<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class TestController extends \BaseController
{
	public function testMail()
	{
		$delay = 0;

		return Mail::later(Carbon::now()->addSeconds($delay), 'emails.test', array(), function ($message) {
			$message->to('zahir@odesi.tech', 'Zahir | ODESI')->subject('Test E-mail');
		});
	}
}
