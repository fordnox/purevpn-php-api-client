<?php

namespace Fordnox\Test\Purevpn;

use Fordnox\Purevpn\Account;
use Fordnox\Purevpn\Purevpn;

class PurevpnTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAccount()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getType', 'getPeriod'));
        $accountMock->expects($this->once())
            ->method('getType')
            ->will($this->returnValue('HIGH-BW'));
        $accountMock->expects($this->once())
            ->method('getPeriod')
            ->will($this->returnValue('#180'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('user'=>'vpn_username', 'vpn_password'=>'vpn_password', 'result'=>'1')));

        $account = $purevpnMock->createAccount($accountMock);
        $this->assertTrue($account->isEnabled());
        $this->assertEquals('vpn_username',$account->getUsername());
        $this->assertEquals('vpn_password',$account->getPassword());
    }

    public function testRenewAccount()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getUsername', 'getPeriod'));
        $accountMock->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('vpn_username'));
        $accountMock->expects($this->once())
            ->method('getPeriod')
            ->will($this->returnValue('#180'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('user'=>'vpn_username', 'vpn_password'=>'vpn_password', 'result'=>'1')));

        $bool = $purevpnMock->renewAccount($accountMock);
        $this->assertTrue($bool);
    }

    public function testDisableAccount()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getUsername'));
        $accountMock->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('vpn_username'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('result'=>'1')));

        $bool = $purevpnMock->disableAccount($accountMock);
        $this->assertTrue($bool);
    }

    public function testEnableAccount()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getUsername'));
        $accountMock->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('vpn_username'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('result'=>'1')));

        $bool = $purevpnMock->enableAccount($accountMock);
        $this->assertTrue($bool);
    }

    public function testChangeAccountPassword()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getUsername'));
        $accountMock->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('vpn_username'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('result'=>'1')));

        $bool = $purevpnMock->changeAccountPassword($accountMock);
        $this->assertTrue($bool);
    }

    public function testGetAccountStatus()
    {
        $accountMock = $this->getMock('Fordnox\Purevpn\Account', array('getUsername'));
        $accountMock->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('vpn_username'));

        $purevpnMock = $this->getMock('Fordnox\Purevpn\Purevpn', array('_request'));
        $purevpnMock
            ->expects($this->once())
            ->method('_request')
            ->will($this->returnValue(array('result'=>'1', 'status'=>'Disabled:February 20, 2013')));

        $accountMock->setStatus('enabled');
        $account = $purevpnMock->getAccountStatus($accountMock);
        $this->assertFalse($account->isEnabled());
    }

    public function testParseXml()
    {
        $xml = "
            <xml>
                <user>Username</user>
                <vpn_password>Password</vpn_password>
                <result>1</result>
            </xml>
        ";
        $class = new \ReflectionClass('Fordnox\Purevpn\Purevpn');
        $method = $class->getMethod('__parseResponse');
        $method->setAccessible(true);
        $array = $method->invokeArgs(new Purevpn(), array($xml));
        $this->assertEquals(array('user' => 'Username', 'vpn_password' => 'Password', 'result' => '1'), $array);
    }
}