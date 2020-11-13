<?php
declare(strict_types = 1);
namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/** @Embeddable */
class Address {
    /** @Column(type="string") **/
    private $street;

    /** @Column(type="string") **/
    private $streetNumber;

    /** @Column(type="string") **/
    private $city;

    /** @Column(type="string") **/
    private $zipcode;

    public function __construct(string $street, string $streetNumber, string $city, string $zipcode)
    {
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->city = $city;
        $this->zipcode = $zipcode;
    }
    
    public function getStreet(): string
    {
        return $this->street;
    }
    
    public function setStreet(string $street)
    {
        $this->street = $street;
    }
    
    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }
    
    public function setStreetNumber(int $streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }
    
    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }
    
    public function setZipcode(int $zipcode)
    {
        $this->zipcode = $zipcode;
    }
}