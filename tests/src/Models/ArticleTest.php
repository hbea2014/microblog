<?php namespace Microblog\Test;

use Microblog\Article;

class ArticleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Microblog\Article::populate
     */
    public function populate_sets_article_properties_correctly()
    {
        $articleId = 1;
        $title = 'First title for the first article';
        $content = '<h1>First Article</h1><p>Some content.</p>';
        $data = [
            'articleId' => $articleId,
            'title' => $title,
            'content' => $content
        ];

        $article = new Article($data);

        $this->assertEquals($articleId, $article->getArticleId());
        $this->assertEquals($title, $article->getTitle());
        $this->assertEquals($content, $article->getContent());
    }

    /**
     * @test
     * @covers Microblog\Article::toArray
     */
    public function toArray_returns_article_properties_correctly()
    {
        $articleId = 1;
        $title = 'First title for the first article';
        $content = '<h1>First Article</h1><p>Some content.</p>';
        $data = [
            'articleId' => $articleId,
            'title' => $title,
            'content' => $content
        ];

        $article = new Article($data);
        $articleDataAsArray = $article->toArray();

        $this->assertEquals($articleId, $articleDataAsArray['articleId']);
        $this->assertEquals($title, $articleDataAsArray['title']);
        $this->assertEquals($content, $articleDataAsArray['content']);
    }
}