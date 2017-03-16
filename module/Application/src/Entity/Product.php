<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="category_id_key", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\ProductRepository")
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
     *     "label":"Designation",
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
     * @Annotation\Filter({"name":"stringTrim"})
     * @Annotation\Validator({
     *     "name":"regex",
     *     "options":{
     *         "pattern":"/^\d+[.]?\d+$/",
     *         "messages":{
     *             "regexNotMatch":"Please input a valid price"
     *         },
     *     }
     * })
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="availability", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"id":"availability"})
     * @Annotation\Options({
     *     "label":"Availability",
     *     "label_attributes":{"class":"text-right col-sm-2"},
     *     "set_hidden_element":"true",
     *     "checked_value":"1",
     *     "unchecked_value":"0"
     * })
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $availability = "1";

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"class":"form-control", "id":"description", "required":"required"})
     * @Annotation\Options({
     *     "label":"Description",
     *     "min":"2",
     *     "label_attributes":{"class":"control-label col-sm-2"},
     * })
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{"encoding":"utf-8", "min":"2"}})
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"id":"isNew"})
     * @Annotation\Options({
     *     "label":"Is new",
     *     "label_attributes":{"class":"text-right col-sm-2"},
     *     "set_hidden_element":"true",
     *     "checked_value":"1",
     *     "unchecked_value":"0",
     * })
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $isNew = "0";

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"id":"status"})
     * @Annotation\Options({
     *     "label":"Visible",
     *     "label_attributes":{"class":"text-right col-sm-2"},
     *     "set_hidden_element":"true",
     *     "checked_value":"1",
     *     "unchecked_value":"0",
     * })
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $status = "1";

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Name("file")
     * @Annotation\Attributes({"id":"file"})
     * @Annotation\Options({"label":"Upload image", "label_attributes":{"class":"control-label text-right col-sm-2"}})
     * @Annotation\Filter({
     *     "type":"Zend\InputFilter\FileInput",
     *     "name":"FileRenameUpload",
     *     "options":{
     *         "target":"./public_html/img/products/",
     *         "useUploadName":true,
     *         "useUploadExtension":true,
     *         "overwrite":true,
     *         "randomize":false
     *     }
     * })
     */
    private $image = "/img/products/no-image.jpg";

    /**
     * @var \Application\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     * })
     *
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"class":"form-control", "id":"category", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Options({
     *   "label":"Categories",
     *   "label_attributes":{"class":"control-label text-right col-sm-2"},
     *   "empty_option": "Select category",
     *   "target_class":"Application\Entity\Category",
     *   "property": "name"
     * })
     */
    private $category;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Submit"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;


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

