<?php


namespace App\Tests\Model\Message\Service;


use App\Model\Message\Entity\Message;
use App\Model\Message\Service\MessageTextHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageTextHandlerTest extends KernelTestCase
{
    public function testHandleMessage(): void
    {
        $messageHandler = new MessageTextHandler();
        $message = new Message();
        $message->setText(">>>>>123 >>>>>777 Hello there");

        $handledMessageArray = $messageHandler->handleMessage($message);

        $this->assertEquals('Hello there', $handledMessageArray['message']->getText());
        $this->assertEquals([123, 777], $handledMessageArray['listOfParentMessages']);
    }

    public function testHandleMessageWithoutParentMessages(): void
    {
        $messageHandler = new MessageTextHandler();
        $message = new Message();
        $message->setText("Hello there!!! 4445");

        $handledMessageArray = $messageHandler->handleMessage($message);

        $this->assertEquals('Hello there!!! 4445', $handledMessageArray['message']->getText());
        $this->assertEquals([], $handledMessageArray['listOfParentMessages']);
    }

    public function testHandleMessageWithTags(): void
    {
        $messageHandler = new MessageTextHandler();
        $message = new Message();
        $testString = ">>>>>777 <b>Hello</b> there how are <s onclick='code.js'>you!!!</s><a href='asd.js'>click me</a>";
        $message->setText($testString);

        $handledMessageArray = $messageHandler->handleMessage($message);

        $this->assertEquals("<b>Hello</b> there how are <s>you!!!</s>click me", $handledMessageArray['message']->getText());
        $this->assertEquals([777], $handledMessageArray['listOfParentMessages']);
    }
}