<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:58
 */

namespace LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 * @package LouvreBundle\Entity
 *
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\ItemRepository")
 * @ORM\Table(name="items")
 * @ORM\HasLifecycleCallbacks()
 */
class Item
{
    const ADULT_RATE = 1;
    const CHILD_RATE = 2;
    const SENIOR_RATE = 3;
    const REDUCED_RATE = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=45)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=45)
     */
    protected $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date")
     */
    protected $birthDate;

    /**
     * @var \LouvreBundle\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;

    /**
     * @var \LouvreBundle\Entity\Ticket
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Ticket")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * @var \LouvreBundle\Entity\Order
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @var boolean
     */
    protected $isReduceRate;

    /**
     * @var integer
     */
    protected $ticketRate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Item
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Item
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return Item
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return Item
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     * @return Item
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return Item
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReduceRate()
    {
        return $this->isReduceRate;
    }

    /**
     * @param bool $isReduceRate
     * @return Item
     */
    public function setIsReduceRate($isReduceRate)
    {
        $this->isReduceRate = $isReduceRate;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTicketRate()
    {
        return $this->ticketRate;
    }

    /**
     * @param mixed integer
     * @return Item
     */
    public function setTicketRate($ticketRate)
    {
        $this->ticketRate = $ticketRate;
        return$this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function calculateTicketRate()
    {
        // Calculate the age of ticket holder
        $interval = date_diff($this->order->getVenueDate(), $this->birthDate, $differenceFormat = '%y');
        $age = $interval->format($differenceFormat);
        // Calculate the ticket rate : by default is Adult
        $this->ticketRate = self::ADULT_RATE;
        if ($this->isReduceRate()){
            $this->ticketRate = self::REDUCED_RATE;
        } elseif ($age >= 60 ){
            $this->ticketRate = self::SENIOR_RATE;
        }elseif ($age >4 && $age < 12){
            $this->ticketRate = self::CHILD_RATE;
        }
    }

}