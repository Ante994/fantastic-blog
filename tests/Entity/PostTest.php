<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 15:08
 */

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testItHasNoTagByDefault()
    {
        $post = new Post();

        $this->assertEmpty($post->getTags());
    }

    public function testItAddsTags()
    {
        $post = new Post();
        $post->addTag(new Tag());
        $post->addTag(new Tag());

        $this->assertCount(2, $post->getTags());
    }




}

