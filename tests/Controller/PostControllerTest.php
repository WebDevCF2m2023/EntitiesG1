<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/post/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Post::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'post[postTitle]' => 'Testing',
            'post[postText]' => 'Testing',
            'post[postDateCreated]' => 'Testing',
            'post[postDatePublished]' => 'Testing',
            'post[postIsPublished]' => 'Testing',
            'post[sections]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setPostTitle('My Title');
        $fixture->setPostText('My Title');
        $fixture->setPostDateCreated('My Title');
        $fixture->setPostDatePublished('My Title');
        $fixture->setPostIsPublished('My Title');
        $fixture->setSections('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Post');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setPostTitle('Value');
        $fixture->setPostText('Value');
        $fixture->setPostDateCreated('Value');
        $fixture->setPostDatePublished('Value');
        $fixture->setPostIsPublished('Value');
        $fixture->setSections('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'post[postTitle]' => 'Something New',
            'post[postText]' => 'Something New',
            'post[postDateCreated]' => 'Something New',
            'post[postDatePublished]' => 'Something New',
            'post[postIsPublished]' => 'Something New',
            'post[sections]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/post/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPostTitle());
        self::assertSame('Something New', $fixture[0]->getPostText());
        self::assertSame('Something New', $fixture[0]->getPostDateCreated());
        self::assertSame('Something New', $fixture[0]->getPostDatePublished());
        self::assertSame('Something New', $fixture[0]->getPostIsPublished());
        self::assertSame('Something New', $fixture[0]->getSections());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Post();
        $fixture->setPostTitle('Value');
        $fixture->setPostText('Value');
        $fixture->setPostDateCreated('Value');
        $fixture->setPostDatePublished('Value');
        $fixture->setPostIsPublished('Value');
        $fixture->setSections('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/post/');
        self::assertSame(0, $this->repository->count([]));
    }
}
