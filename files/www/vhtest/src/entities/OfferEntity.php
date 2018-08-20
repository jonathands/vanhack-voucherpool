<?php
Namespace VoucherPool\Entity;
use VoucherPool\Utils\JsonSerializeTrait;

class OfferEntity implements \JsonSerializable
{
    use JsonSerializeTrait;

    protected $id;
    protected $name;
    protected $discount;
    protected $expiresAt;

    private $entityName = "voucherpool.offer";
    
    public function __construct(array $data) {
        if(isset($data['id']))
            $this->id = $data['id'];

        $this->name = $data['name'];
        $this->discount = $data['discount'];
        
        if(!isset($data['expires_at']))
            $this->expiresAt = new \DateTime();
        else
            $this->setExpiresAt($data['expires_at']);

    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

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

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
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
}