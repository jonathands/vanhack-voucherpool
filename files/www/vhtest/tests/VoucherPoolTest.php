<?php 
namespace Tests;
use Tests\Functional\BaseTestCase;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class VoucherPoolTest extends BaseTestCase
{
    private $codeTest;
    //Add a voucher
    public function testAddVouchers() {
        $this->codeTest =  \VoucherPool\Utils\Randomizer::randomCode(8);
        $requestBody = array("code" => $this->codeTest);

        $response = $this->runApp('POST','/voucher/save',$requestBody);
        $response->getStatusCode();
        
        $this->assertSame($response->getStatusCode(), 200);
    } 

    //Add Same voucher twice
    public function testAddSameVouchers() {
        $requestBody = array("code" => $this->codeTest);

        $response = $this->runApp('POST','/voucher/use',$requestBody);
        $response->getStatusCode();

        $this->assertSame($response->getStatusCode(), 400);
    } 
    
    //List all vouchers created 
    public function testListVouchers() {
        $response = $this->runApp('GET','/vouchers');
        $response->getStatusCode();
        $data = json_encode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 200);
    } 

}