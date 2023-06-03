<?php

namespace App\Consumer\PutTaskPoints\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('numeric')]
    private int $userId;
    #[Assert\NotBlank]
    private int $taskId;
    #[Assert\LessThanOrEqual(10)]
    private int $points;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->userId = $message['userId'];
        $result->taskId = $message['taskId'];
        $result->points = $message['points'];

        return $result;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getPoints(): int
    {
        return $this->points;
    }
}
