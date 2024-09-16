<?php

namespace App\Tests\Controller;

use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SectionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/section/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Section::class);

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
        self::assertPageTitleContains('Section index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'section[sectionTitle]' => 'Testing',
            'section[sectionDescription]' => 'Testing',
            'section[posts]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Section();
        $fixture->setSectionTitle('My Title');
        $fixture->setSectionDescription('My Title');
        $fixture->setPosts('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Section');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Section();
        $fixture->setSectionTitle('Value');
        $fixture->setSectionDescription('Value');
        $fixture->setPosts('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'section[sectionTitle]' => 'Something New',
            'section[sectionDescription]' => 'Something New',
            'section[posts]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/section/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getSectionTitle());
        self::assertSame('Something New', $fixture[0]->getSectionDescription());
        self::assertSame('Something New', $fixture[0]->getPosts());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Section();
        $fixture->setSectionTitle('Value');
        $fixture->setSectionDescription('Value');
        $fixture->setPosts('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/section/');
        self::assertSame(0, $this->repository->count([]));
    }
}
