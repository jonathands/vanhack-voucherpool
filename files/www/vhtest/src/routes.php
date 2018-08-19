<?php

use Slim\Http\Request;
use Slim\Http\Response;
use VoucherPool\Domain\VoucherPool;
use VoucherPool\Entity\VoucherMapper;
use VoucherPool\Entity\CustomerMapper;
use VoucherPool\Entity\OfferMapper;
use VoucherPool\Entity\VoucherEntity;
use VoucherPool\Entity\CustomerEntity;
use VoucherPool\Entity\OfferEntity;

// Index SPA
$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
});

// List all Vouchers
$app->get('/vouchers', function (Request $request, Response $response, array $args) {
    try
    {    
        $VoucherPool = new VoucherPool($this->db);    
        return $response->withJson($VoucherPool->getVouchers());
    }    
    catch(Exception $e)
    {
        return $response->withJson($e->getMessage(),400);   
    }
});

// Save new voucher
$app->post('/voucher/save', function (Request $request, Response $response, array $args) {
    try
    {    
        $VoucherPool = new VoucherPool($this->db);    

        $data = $request->getParsedBody();

        $emails = explode(";",$data["email"]);
        
        $customers = array();
        foreach($emails as $email)
        {
            $customers[] = new CustomerEntity(array("email"=>$email));
        }

        $offer = new OfferEntity(array("name" => $data["name"], 
                                       "discount" => $data["discount"], 
                                       "expires_at" => $data["expires_at"]
                                      )
                                );        

        $VoucherEntities = $VoucherPool->createVouchersForCostumers($customers,$offer);
   
        return $response->withJson($VoucherEntities);   
    } 
    catch(Exception $e)
    {
        return $response->withJson($e->getMessage(),400);   
    }
});

// Use a voucher
$app->post('/voucher/use', function (Request $request, Response $response, array $args) {
    try
    {
        $VoucherPool = new VoucherPool($this->db);    

        $data = $request->getParsedBody();

        $VoucherEntity = new VoucherEntity($data);
        $CustomerEntity = new CustomerEntity($data);

        $VoucherEntity = $VoucherPool->useVoucher($VoucherEntity,$CustomerEntity);

        return $response->withJson($VoucherEntity);   
    } 
    catch(Exception $e)
    {
        return $response->withJson($e->getMessage(),400);   
    }
});
