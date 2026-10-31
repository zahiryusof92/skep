<?php

use Carbon\Carbon;

class TppmSsoTestUser extends User
{
    public $roles = array();

    public function isCOB() { return in_array('COB', $this->roles); }
    public function isJMB() { return in_array('JMB', $this->roles); }
    public function isMC() { return in_array('MC', $this->roles); }
    public function isDeveloper() { return in_array('DEVELOPER', $this->roles); }
}

class TppmSsoCutoffTest extends TestCase
{
    public function tearDown()
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function at($date, $until = '2026-10-31')
    {
        Config::set('constant.third_party.estrata.tppm_file_scoped_sso_until', $until);
        Carbon::setTestNow(Carbon::parse($date));
    }

    private function user(array $roles)
    {
        $user = new TppmSsoTestUser;
        $user->roles = $roles;

        return $user;
    }

    public function testStillOpenOnLastDay()
    {
        $this->at('2026-10-31 23:59:00');

        $this->assertFalse(User::tppmFileScopedSsoClosed());
        $this->assertFalse($this->user(array('JMB'))->isTppmSsoClosed());
    }

    public function testClosedFromFirstOfNovember()
    {
        $this->at('2026-11-01 00:00:00');

        $this->assertTrue($this->user(array('JMB'))->isTppmSsoClosed());
        $this->assertTrue($this->user(array('MC'))->isTppmSsoClosed());
    }

    public function testCobStaysOpenAfterCutoff()
    {
        $this->at('2026-11-01 00:00:00');

        $this->assertFalse($this->user(array('COB'))->isTppmSsoClosed());
        $this->assertFalse($this->user(array('JMB', 'COB'))->isTppmSsoClosed());
    }

    public function testNeverClosesWhenDateEmptyOrInvalid()
    {
        foreach (array('', 'not-a-date') as $until) {
            $this->at('2030-01-01 00:00:00', $until);

            $this->assertFalse(User::tppmFileScopedSsoClosed());
        }
    }
}
