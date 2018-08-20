<?php 
namespace Tests;
use Tests\Functional\BaseTestCase;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class VoucherPoolTest extends BaseTestCase
{
    //Add a voucher
    public function testAddVouchers()
    {
        $requestBody = array(
                                "name" => "10% Discount",
                                "discount" => "10",
                                "expires_at" => "01/01/2019",
                                "email" => "jonathands@gmail.com"
                            );

        $response = $this->runApp('POST', '/voucher/save', $requestBody);
        $response->getStatusCode();

        $response->getBody()->rewind();
        $contents = $response->getBody()->getContents();
        $body = json_decode($contents);

        $this->assertTrue($body[0]->code !== "");
     
        $body[0]->code;
        $this->assertSame($response->getStatusCode(), 200);

        return $body[0]->code;
    }

    //Use a voucher
    /**
     * @depends testAddVouchers
     */
    public function testUseVoucher($voucherCode)
    {
        $requestBody = array(
                            "code" => $voucherCode,
                            "email" => "jonathands@gmail.com"
                            );

        $response = $this->runApp('POST', '/voucher/use', $requestBody);

        $response->getBody()->rewind();
        $contents = $response->getBody()->getContents();
        $body = json_decode($contents);

        $this->assertTrue($body->discount !== 0);
        $this->assertSame($response->getStatusCode(), 200);
        
        return $voucherCode;
    }
    

    //Use the Same voucher twice
    /**
     * @depends testUseVoucher
     */
    public function testUseSameVoucher($voucherCode)
    {
        $requestBody = array(
            "code" => $voucherCode,
            "email" => "jonathands@gmail.com"
            );

        $response = $this->runApp('POST', '/voucher/use', $requestBody);

        $response->getBody()->rewind();
        $this->assertSame(json_decode($response->getBody()->getContents()), "Voucher code already used");
        $this->assertSame($response->getStatusCode(), 400);
        return $voucherCode;
    }
    
    //Use a voucher with wrong email
    /**
     * @depends testUseSameVoucher
     */
    public function testUseWrongCustomerVoucher($voucherCode)
    {
        $requestBody = array(
            "code" => $voucherCode,
            "email" => "notjonathands@gmail.com"
            );

        $response = $this->runApp('POST', '/voucher/use', $requestBody);

        $response->getBody()->rewind();
        $this->assertSame(json_decode($response->getBody()->getContents()), "No such Customer");
        $this->assertSame($response->getStatusCode(), 400);
    }
    
    //List all vouchers created
    public function testListVouchers()
    {
        $response = $this->runApp('GET', '/vouchers');
        $response->getStatusCode();
        
        $response->getBody()->rewind();
        $data = json_decode($response->getBody()->getContents(), true);
        
        $this->assertGreaterThan(0, count($data));
        $this->assertSame($response->getStatusCode(), 200);
    }
}
