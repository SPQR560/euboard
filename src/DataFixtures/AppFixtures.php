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

    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName("bulls**t");
        $board->setPath("b");
        $manager->persist($board);

        $thread = new Thread();
        $thread->setName("Test");
        $thread->setBoard($board);
        $thread->setCreationTime(new DateTimeImmutable());
        $manager->persist($thread);

        $messageFirstThread = new Message();
        $messageFirstThread->setText("test ok");
        $messageFirstThread->setTime(new DateTimeImmutable());
        $messageFirstThread->setThread($thread);
        $manager->persist($messageFirstThread);

        $thread2 = new Thread();
        $thread2->setName("How are you?");
        $thread2->setBoard($board);
        $thread2->setCreationTime(new DateTimeImmutable());
        $manager->persist($thread2);

        $messageSecondThread = new Message();
        $messageSecondThread->setText("I am fine");
        $messageSecondThread->setTime(new DateTimeImmutable());
        $messageSecondThread->setThread($thread2);
        $manager->persist($messageSecondThread);

        $messageSecondThread2 = new Message();
        $messageSecondThread2->setText("I am fine too");
        $messageSecondThread2->setTime(new DateTimeImmutable());
        $messageSecondThread2->setThread($thread2);
        $manager->persist($messageSecondThread2);
        $manager->persist(new ChildMessages($messageSecondThread2, $messageSecondThread));

        $messageSecondThread3 = new Message();
        $messageSecondThread3->setText("And me too");
        $messageSecondThread3->setTime(new DateTimeImmutable());
        $messageSecondThread3->setThread($thread2);
        $manager->persist($messageSecondThread3);
        $manager->persist(new ChildMessages($messageSecondThread3, $messageSecondThread));

//        $admin = new User();
//        $admin->setEmail("admin@test.ru");
//        $admin->setRoles(['ROLE_ADMIN']);
//
//        // for dev only!
//        $encodedPassword = $this->encoderFactory->getEncoder(User::class)
//            ->encodePassword('123456', null);
//
//        $admin->setPassword($encodedPassword);
//        $manager->persist($admin);

        $manager->flush();
    }
}
