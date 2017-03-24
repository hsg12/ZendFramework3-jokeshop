<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * ProductOrder
 *
 * @ORM\Table(name="product_order", indexes={@ORM\Index(name="user_id_key", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ProductOrderRepository")
 *
 * @Annotation\Name("productOrder")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class ProductOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Annotation\Exclude()
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     *
     * @Annotation\Exclude()
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=100, nullable=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"userName", "disabled":"disabled"})
     * @Annotation\Options({"label":"Name"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"1", "max":"100"}})
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=100, nullable=false)
     *
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Attributes({"class":"form-control", "id":"userEmail", "disabled":"disabled"})
     * @Annotation\Options({"label":"Email"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"emailAddress", "options":{"encoding":"utf-8", "min":"1", "max":"100"}})
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="user_phone", type="string", length=255, nullable=true)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"userPhone"})
     * @Annotation\Options({"label":"Phone"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({
     *     "name":"regex",
     *     "options":{
     *         "pattern":"/^[0-9()+ -]+$/",
     *         "messages":{
     *             "regexNotMatch":"Please input a valid phone"
     *         },
     *     }
     * })
     */
    private $userPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="user_address", type="text", length=65535, nullable=true)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"userAddress", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Options({"label":"Address", "min":"2"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2"}})
     */
    private $userAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="products", type="text", length=65535, nullable=true)
     *
     * @Annotation\Exclude()
     */
    private $products;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="datetime", nullable=true)
     *
     * @Annotation\Exclude()
     */
    private $orderDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=false)
     *
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Attributes({"class":"form-control", "id":"status", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags"})
     * @Annotation\Validator({"name":"InArray", "options":{
     *     "haystack":{"Is accepted", "In progress", "Late", "Delivered", "Canceled"},
     *     "messages":{"notInArray":"Please Select a right value"},
     * }})
     * @Annotation\Options({
     *     "label":"Status",
     *     "empty_option": "Select category",
     *     "value_options":{"Is accepted":"Is accepted", "In progress":"In Progress", "Late":"Late", "Delivered":"Delivered", "Canceled":"Canceled"}
     * })
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $status = 'Is accepted';

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Order"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;

    public function __construct()
    {
        $this->orderDate = new \DateTime();
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return ProductOrder
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return ProductOrder
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return ProductOrder
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set userPhone
     *
     * @param string $userPhone
     *
     * @return ProductOrder
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    /**
     * Get userPhone
     *
     * @return string
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * Set userAddress
     *
     * @param string $userAddress
     *
     * @return ProductOrder
     */
    public function setUserAddress($userAddress)
    {
        $this->userAddress = $userAddress;

        return $this;
    }

    /**
     * Get userAddress
     *
     * @return string
     */
    public function getUserAddress()
    {
        return $this->userAddress;
    }

    /**
     * Set products
     *
     * @param string $products
     *
     * @return ProductOrder
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get products
     *
     * @return string
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return ProductOrder
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return ProductOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
