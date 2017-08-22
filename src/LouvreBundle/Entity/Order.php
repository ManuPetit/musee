<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:48
 */

namespace LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @package LouvreBundle\Entity
 *
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="datetime")
     */
    protected $orderDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="venue_date", type="date")
     */
    protected $venueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", length=20)
     */
    protected $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_email", type="string", length=100)
     */
    protected $customerEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="order_paid", type="boolean")
     */
    protected $orderPaid;

    /**
     * @var \LouvreBundle\Entity\Duration
     *
     * @ORM\ManyToOne(targetEntity="LouvreBundle\Entity\Duration")
     * @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
     */
    protected $duration;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LouvreBundle\Entity\Item", mappedBy="order", cascade={"persist"})
     */
    protected $items;

    public function __construct()
    {
        $this->orderDate = new \DateTime();
        $this->orderPaid = false;
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @param \DateTime $orderDate
     * @return Order
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getVenueDate()
    {
        return $this->venueDate;
    }

    /**
     * @param \DateTime $venueDate
     * @return Order
     */
    public function setVenueDate($venueDate)
    {
        $this->venueDate = $venueDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return Order
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     * @return Order
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrderPaid()
    {
        return $this->orderPaid;
    }

    /**
     * @param bool $orderPaid
     * @return Order
     */
    public function setOrderPaid($orderPaid)
    {
        $this->orderPaid = $orderPaid;
        return $this;
    }

    /**
     * @return Duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param Duration $duration
     * @return Order
     */
    public function setDuration(Duration $duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(ArrayCollection $items)
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
     * @return Order
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
        $item->setOrder($this);

        return $this;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * @ORM\PrePersist()
     */
    public function generateOrderNumber()
    {
        //generate random 8 letters word
        $letter = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($letter);
        $word = substr(implode($letter), 0, 8);
        //create order number with order date, random word
        //and venue date
        $this->orderNumber = date_format($this->orderDate, 'ymd')
            . $word
            . date_format($this->venueDate, 'ymd');
    }


}