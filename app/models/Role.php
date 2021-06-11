<?php

class Role extends Eloquent {

    protected $table = 'role';

    const SUPERADMIN = 'SUPERADMIN';
    const COB_MANAGER = 'COB MANAGER';
    const COB = 'COB';
    const JMB = 'JMB';
    const MC = 'MC';
    const LAWYER = 'LAWYER';
    const HR = 'HR';
    const COB_BASIC	 = 'COB BASIC';
    const COB_BASIC_ADMIN = 'COB BASIC ADMIN';
    const COB_PREMIUM = 'COB PREMIUM';
    const COB_PREMIUM_ADMIN = 'COB PREMIUM ADMIN';

}
