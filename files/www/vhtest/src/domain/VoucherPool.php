<?php
namespace VoucherPool\Domain;

use VoucherPool\Entity\VoucherMapper;
use VoucherPool\Entity\VoucherEntity;
use VoucherPool\Entity\CustomerMapper;
use VoucherPool\Entity\OfferMapper;

class VoucherPool
{
    protected $voucherMapper;
    protected $customerMapper;
    protected $offerMapper;

    public function __construct($db)
    {
        $this->voucherMapper = new VoucherMapper($db);
        $this->customerMapper = new CustomerMapper($db);
        $this->offerMapper = new OfferMapper($db);
    }

    public function getVouchers()
    {
        return $this->voucherMapper->getList();
    }

    public function getVoucherBycustomer($customer)
    {
        $voucherByCustomer = $this->voucherMapper->getOneByFilter(["customer_id" => $customer->getId()]);

        return $voucherByCustomer;
    }
    public function getCustomers()
    {
        return $this->customerMapper->getList();
    }
    
    public function getVoucher($voucherEntity)
    {
        $this->voucherMapper->get($voucherEntity);
    }

    public function removeVoucher($voucherEntity)
    {
        return $this->voucherMapper->delete($voucherEntity);
    }
    
    public function createVouchersForCostumers($customers, $offer)
    {
        $offer = $this->offerMapper->save($offer);

        $vouchers = array();
        foreach ($customers as $costumer) {
            $costumerByEmail = $this->customerMapper->getOneByFilter(["email" => $costumer->getEmail()]);
            if (false !== $costumerByEmail) {
                $costumer = $costumerByEmail;
            }

            $voucher =  new VoucherEntity(array("customer_id" => $costumer->getId(),
                                                "offer_id" => $offer->getId(),
                                                "expirates_at" => $offer->getExpiresAt(),
                                                "code" => \VoucherPool\Utils\Randomizer::randomCode(8)
                                               )
                                         );

            $vouchers[] = $this->voucherMapper->save($voucher);
        }

        return  $vouchers;
    }
    
    public function useVoucher($voucher, $customer)
    {
        $customerByEmail = $this->customerMapper->getOneByFilter(["email" => $customer->getEmail()]);
        if (false !== $customerByEmail) {
            $customer = $customerByEmail;
        } else {
            throw new \Exception("No such Customer");
        }

        $voucher = $this->voucherMapper->getOneByFilter(["code" => $voucher->getCode()]);
        if ($voucher) {
            if ($voucher->getUsedAt())
                throw new \Exception("Voucher code already used");

            $this->voucherMapper->saveUsage($voucher, $customer);

            $offer = $this->offerMapper->getOne($voucher->getOfferId());
            return array("discount" => $offer->getDiscount());
        } else {
            throw new \Exception("No such voucher");
        }
    }
    
}