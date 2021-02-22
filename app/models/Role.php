<?php

class Role extends Eloquent {

    protected $table = 'role';

    const SUPERADMIN = 'SUPERADMIN';
    const COB_MANAGER = 'COB MANAGER';
    const COB = 'COB';
    const JMB = 'JMB';
    const MC = 'MC';
    const LAWYER = 'LAWYER';

}
