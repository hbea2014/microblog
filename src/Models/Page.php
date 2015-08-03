<?php namespace Microblog\Models;

class Page extends Model
{

    /**
     * @var integer The ID of the page
     */
    private $id;

    /**
     * @var string The pretty URL of the page
     */
    private $url;

    /**
     * @var string The title of the page
     */
    private $title;

    /**
     * @var string The content of the page
     */
    private $content;

    /**
     * @var array The parameters required to populate the model
     */
    protected $required = ['id', 'url', 'title', 'content'];

    /**
     * @param integer The ID of the page
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer The ID of the page
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $url The url of the page
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string The url of the page
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $title The title of the page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string The title of the page
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $content The content of the page
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string The content of the page
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Populates the page with data
     * 
     * @param array $row The row data to populate the page with
     */
    public function populate(array $row)
    {
        if ( $this->hasRequiredParams($row) ) {
            $this->setId($row['id']);
            $this->setUrl($row['url']);
            $this->setTitle($row['title']);
            $this->setContent($row['content']);
        }

        return $this;
    }

    /**
     * Return the page as an array
     * 
     * @return array The page data
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ];
    }
}
