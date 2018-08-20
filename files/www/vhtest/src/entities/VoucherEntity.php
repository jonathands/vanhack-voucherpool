<?php
namespace VoucherPool\Entity;

use VoucherPool\Utils\JsonSerializeTrait;

class VoucherEntity implements \JsonSerializable
{
    use JsonSerializeTrait;

    protected $id;
    protected $code;
    protected $expiresAt;
    protected $offerId;
    protected $customerId;
    protected $usedAt;
    
    public function __construct(array $data)
    {
        if (isset($data['voucher_id'])) {
            $this->id = $data['voucher_id'];
        }

        if (isset($data['offer_id'])) {
            $this->offerId = $data['offer_id'];
        }
        
        if (isset($data['customer_id'])) {
            $this->customerId = $data['customer_id'];
        }

        $this->code = $data['code'];

        if (!isset($data['expires_at'])) {
            $this->expiresAt = new \DateTime();
        } else {
            $this->setexpiresAt($data['expires_at']);
        }

        if (isset($data['used_at'])) {
            $this->setUsedAt($data['used_at']);
        }
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = \DateTime::createFromFormat("d/m/Y", $expiresAt);

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }
 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getUsedAt()
    {
        return $this->usedAt;
    }

    public function setUsedAt($usedAt)
    {
        $this->usedAt = \DateTime::createFromFormat("d/m/Y", $usedAt);

        return $this;
    }

    public function getOfferId()
    {
        return $this->offerId;
    }

    public function setOfferId($offerId)
    {
        $this->offerId = $offerId;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }
}