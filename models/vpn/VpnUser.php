<?php

namespace app\models\vpn;

class VpnUser {
    /** @var string AD login*/
    public $login;
    /** @var VpnIpPoolRecord[] Assigned ip addresses */
    public $IPAddresses = [];
    /** @var Binary Zip-packed certs */
    public $ConnectionKit;
    /** @var VpnRequestDocRecord Request for connection */
    public $VpnRequestDoc;
    /** @var WorkstationsRecord Description*/
    public $OppenedRDP = [];
}
