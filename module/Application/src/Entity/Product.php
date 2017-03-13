<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="category_id_key", columns={"category_id"})})
 * @ORM\Entity
 *
 * @Annotation\Name("product")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Annotation\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"name", "required":"required"})
     * @Annotation\Options({
     *     "label":"Product name",
     *     "min":"2", "max":"100",
     *     "label_attributes":{"class":"control-label col-sm-2"},
     * })
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2", "max":"100"}})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"price", "required":"required"})
     * @Annotation\Options({"label":"Price", "label_attributes":{"class":"control-label col-sm-2"}})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\Validator({"name":"isFloat"})
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="availability", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"id":"availability"})
     * @Annotation\Options({"label":"Availability", "label_attributes":{"class":"text-right col-sm-2"}})
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\Validator({"name":"isFloat"})
     */
    private $availability = "1";

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isNew;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $image;

    /**
     * @var \Application\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $category;


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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set availability
     *
     * @param boolean $availability
     *
     * @return Product
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return boolean
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     *
     * @return Product
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param \Application\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\Application\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Application\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}

