<?php

namespace App\DataFixtures;

use App\Model\Board\Entity\Board;
use App\Model\Message\Entity\ChildMessages;
use App\Model\Message\Entity\Message;
use App\Model\Thread\Entity\Thread;
use App\Model\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function setLoremImpsumText(): string
    {
        return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam malesuada eu metus non pharetra.
         In molestie nisl non nulla lacinia pulvinar. Nam eu aliquam libero. Sed lacinia luctus feugiat. Sed nisi nisi,
          vulputate nec ultricies nec, volutpat eget est. Nulla id mollis ex. Nulla in odio sed enim scelerisque
           vestibulum. Mauris rutrum nunc neque, nec congue neque elementum sed. Ut mi metus, auctor at ipsum ut,
            lacinia accumsan nisi. Aliquam aliquam turpis accumsan, bibendum augue sit amet, ullamcorper purus.
             Donec purus urna, maximus ac augue aliquet, tempor hendrerit risus.';
    }

    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName('Random');
        $board->setPath('b');

        $manager->persist($board);

        $anotherBoard = new Board();
        $anotherBoard->setName('Software developing');
        $anotherBoard->setPath('dev');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Politics');
        $anotherBoard->setPath('po');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Auto');
        $anotherBoard->setPath('au');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Bikes');
        $anotherBoard->setPath('bi');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Books');
        $anotherBoard->setPath('bo');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('History');
        $anotherBoard->setPath('hi');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Movies');
        $anotherBoard->setPath('mov');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Music');
        $anotherBoard->setPath('mu');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Science');
        $anotherBoard->setPath('sc');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('News');
        $anotherBoard->setPath('news');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Video games');
        $anotherBoard->setPath('vg');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Hardware');
        $anotherBoard->setPath('hw');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Religion');
        $anotherBoard->setPath('rel');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Psychology');
        $anotherBoard->setPath('psy');

        $manager->persist($anotherBoard);

        $anotherBoard = new Board();
        $anotherBoard->setName('Philosophy');
        $anotherBoard->setPath('phi');

        $manager->persist($anotherBoard);

        $thread = new Thread();
        $thread->setName('Test');
        $thread->setBoard($board);
        $thread->setText($this->setLoremImpsumText());
        $thread->setCreationTime(new DateTimeImmutable());

        $manager->persist($thread);

        $messageFirstThread = new Message();
        $messageFirstThread->setText('test ok');
        $messageFirstThread->setTime(new DateTimeImmutable());
        $messageFirstThread->setThread($thread);

        $manager->persist($messageFirstThread);

        $thread2 = new Thread();
        $thread2->setName('How are you?');
        $thread2->setBoard($board);
        $thread2->setText('Please answer on my question');
        $thread2->setCreationTime(new DateTimeImmutable());

        $manager->persist($thread2);

        $messageSecondThread = new Message();
        $messageSecondThread->setText('I am fine');
        $messageSecondThread->setTime(new DateTimeImmutable());
        $messageSecondThread->setThread($thread2);

        $manager->persist($messageSecondThread);

        $messageSecondThread2 = new Message();
        $messageSecondThread2->setText('I am fine too');
        $messageSecondThread2->setTime((new DateTimeImmutable())->add(new \DateInterval('PT1H')));
        $messageSecondThread2->setThread($thread2);

        $manager->persist($messageSecondThread2);
        $manager->persist(new ChildMessages($messageSecondThread2, $messageSecondThread, $thread2));

        $messageSecondThread3 = new Message();
        $messageSecondThread3->setText('And me too');
        $messageSecondThread3->setTime((new DateTimeImmutable())->add(new \DateInterval('PT1H30M')));
        $messageSecondThread3->setThread($thread2);

        $manager->persist($messageSecondThread3);
        $manager->persist(new ChildMessages($messageSecondThread3, $messageSecondThread, $thread2));

        $admin = new User();
        $admin->setEmail('admin@test.ru');
        $admin->setRoles(['ROLE_ADMIN']);

        // for dev only!
        $encodedPassword = $this->encoderFactory->getEncoder(User::class)
            ->encodePassword('123456', null);

        $admin->setPassword($encodedPassword);
        $manager->persist($admin);

        $manager->flush();
    }
}
