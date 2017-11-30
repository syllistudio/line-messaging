<?php

namespace Syllistudio\LineMessaging\MessageBuilder;

/**
 * The interface that has a responsibility to build message.
 *
 * @link https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/MessageBuilder.php
 */
interface MessageBuilder
{
    /**
     * Builds message structure.
     *
     * @return array Built message structure.
     */
    public function buildMessage();
}