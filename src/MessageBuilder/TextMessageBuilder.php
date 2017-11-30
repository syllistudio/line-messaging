<?php

namespace Syllistudio\LineMessaging\MessageBuilder;

/**
 * A builder class for text message.
 *
 * @link https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/MessageBuilder/TextMessageBuilder.php
 */
class TextMessageBuilder implements MessageBuilder
{
    /** @var string[] */
    private $texts;
    /** @var array */
    private $message = [];
    /**
     * TextMessageBuilder constructor.
     *
     * Exact signature of this constructor is <code>new TextMessageBuilder(string $text, string[] $extraTexts)</code>.
     *
     * Means, this constructor can also receive multiple messages like so;
     *
     * <code>
     * $textBuilder = new TextMessageBuilder('text', 'extra text1', 'extra text2', ...);
     * </code>
     *
     * @param string $text
     * @param string[]|null $extraTexts
     */
    public function __construct($text, $extraTexts = null)
    {
        $extra = [];
        if (!is_null($extraTexts)) {
            $args = func_get_args();
            $extra = array_slice($args, 1);
        }
        $this->texts = array_merge([$text], $extra);
    }
    /**
     * Builds text message structure.
     *
     * @return array
     */
    public function buildMessage()
    {
        if (!empty($this->message)) {
            return $this->message;
        }
        foreach ($this->texts as $text) {
            $this->message[] = [
                'type' => MessageType::TEXT,
                'text' => $text,
            ];
        }
        return $this->message;
    }
}