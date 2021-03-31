<?php
declare(strict_types=1);

namespace App\Model\Message\Service;


use App\Model\Message\Entity\Message;

class MessageTextHandler
{
    /**
     * @param Message $message
     * @return array
     */
    public function handleMessage(Message $message): array
    {
        $parentMessagesRegexPattern = "/>>>>>([\d+]*)/";
        $removeTagAttributesPattern = "/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si";

        $listOfParentMessages = $this->getParentMessages($message->getText(), $parentMessagesRegexPattern);
        $this->removeExcessTagsAndParentMessagesLinksFromMessage($message, $parentMessagesRegexPattern, $removeTagAttributesPattern);

        return ['message' => $message, 'listOfParentMessages' => $listOfParentMessages];
    }

    /**
     * @param string $message
     * @param string $parentMessagesRegexPattern
     * @return array
     */
    private function getParentMessages(string $message, string $parentMessagesRegexPattern): array
    {
        $listOfParentMessages = [];
        preg_match_all($parentMessagesRegexPattern, $message, $matchesArray, PREG_PATTERN_ORDER);
        if (count($matchesArray) == 2) {
            $listOfParentMessages = array_map(function ($el) {
                return intval($el);
            }, $matchesArray[1]);
        }
        return $listOfParentMessages;
    }

    /**
     * @param Message $message
     * @param string $removeParentMessagesRegexPattern
     * @param string $removeTagAttributesPattern
     */
    private function removeExcessTagsAndParentMessagesLinksFromMessage(Message $message, string $removeParentMessagesRegexPattern, string $removeTagAttributesPattern): void
    {
        $textWithoutParentMessages = trim(preg_replace(
            $removeParentMessagesRegexPattern,
            '',
            $message->getText()
        ));

        //xss secure
        $textWithoutParentMessages = strip_tags($textWithoutParentMessages, ['<b>', '<s>']);
        $textWithoutTagAttributes = preg_replace($removeTagAttributesPattern,'<$1$2>', $textWithoutParentMessages);

        $message->setText($textWithoutTagAttributes);
    }
}