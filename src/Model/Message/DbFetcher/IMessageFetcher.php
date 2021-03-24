<?php
declare(strict_types=1);

namespace App\Model\Message\DbFetcher;


interface IMessageFetcher
{
    function getMessages(int $threadId):array;
}