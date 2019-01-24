<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 15:08
 */

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\PostTranslation;
use App\Entity\Tag;
use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /** @var Post $post */
    private $post;

    public function setUp()
    {
        $this->post = new Post();
    }

    public function testNewPostHasNoTagByDefault()
    {
        $this->assertEmpty($this->post->getTags());
    }

    public function testItAddsTags()
    {
        $this->post->addTag(new Tag());
        $this->post->addTag(new Tag());

        $this->assertCount(2, $this->post->getTags());
    }

    public function testItCreatePost()
    {
        $content = new PostTranslation();
        $content->setTitleEn('main title');
        $content->setContentEn('main content');
        $this->post->setPostTranslation($content);
        $this->assertEquals('main title', $this->post->getPostTranslation()->getTitleEn());

    }

    public function testPostSlugIsCreatedFromTitle()
    {
        $content = new PostTranslation();
        $content->setTitleEn('fantastic title');
        $this->post->setPostTranslation($content);
        $this->post->getPostTranslation()->setSlugEn(Slugify::create()->slugify($content->getTitleEn()));

        $this->assertEquals('fantastic-title', $this->post->getPostTranslation()->getSlugEn());
    }
}
