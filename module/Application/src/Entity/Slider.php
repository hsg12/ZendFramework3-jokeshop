<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Slider
 *
 * @ORM\Table(name="slider")
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\SliderRepository")
 *
 * @Annotation\Name("slider")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class Slider
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
     * @ORM\Column(name="title", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"title"})
     * @Annotation\Options({"label":"Title", "min":"2", "max":"100", "label_attributes":{"class":"control-label col-sm-2"}})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{
     *     "encoding":"utf-8",
     *     "min":"2",
     *     "max":"100",
     * }})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"text"})
     * @Annotation\Options({"label":"Text", "min":"2", "max":"100", "label_attributes":{"class":"control-label col-sm-2"}})
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({"name":"stringLength", "options":{
     *     "encoding":"utf-8",
     *     "min":"2",
     *     "max":"100",
     * }})
     */
    private $text;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"class":"form-control", "id":"visible"})
     * @Annotation\Options({
     *     "label":"Is visible",
     *     "label_attributes":{"class":"text-right col-sm-2"},
     *     "set_hidden_element":"true",
     *     "checked_value":"1",
     *     "unchecked_value":"0"
     * })
     * @Annotation\Filter({"name":"boolean"})
     * @Annotation\AllowEmpty({"allowempty":"false"})
     */
    private $visible = "1";

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
     *         "target":"./public_html/img/slider/",
     *         "useUploadName":true,
     *         "useUploadExtension":true,
     *         "overwrite":true,
     *         "randomize":false
     *     }
     * })
     */
    private $image = "/img/slider/no-image.jpg";

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
     * Set title
     *
     * @param string $title
     *
     * @return Slider
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Slider
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Slider
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Slider
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
}

