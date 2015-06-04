<?php namespace Microblog;

class Article extends Model
{

    /**
     * @var int The ID of the article
     */
    private $articleId;

    /**
     * @var string The title of the article
     */
    private $title;

    /**
     * @var string The content of the article
     */
    private $content;

    /**
     * @param int The ID of the article
     */
    public function setArticleId($articleId) 
    {
        $this->articleId = $articleId;
    }

    /**
     * @return int The ID of the article
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param string $title The title of the article
     */
    public function setTitle($title) 
    {
        $this->title = $title;
    }

    /**
     * @return string The title of the article
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $content The content of the article
     */
    public function setContent($content) 
    {
        $this->content = $content;
    }

    /**
     * @return string The content of the article
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Populate article with data
     * 
     * @param array $row
     */
    public function populate($row) {
        $this->setArticleId($row['articleId']);
        $this->setTitle($row['title']);
        $this->setContent($row['content']);
    }

    /**
     * Return article as an array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'articleId' => $this->getArticleId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ];
    }
}
