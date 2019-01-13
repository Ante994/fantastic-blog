<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 15:08
 */

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\PostDetail;
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
        $this->post->setTitle('main title');
        $content = new PostDetail();
        $content->setContent('main content');
        $this->post->setPostDetail($content);

        $this->assertEquals('main content', $this->post->getPostDetail()->getContent());
    }

    public function testPostSlugIsCreatedFromTitle()
    {
        $this->post->setTitle('fantastic title');
        $this->post->setSlug(Slugify::create()->slugify($this->post->getTitle()));

        $this->assertEquals('fantastic-title', $this->post->getSlug());
    }
}
