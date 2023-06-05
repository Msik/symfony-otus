<?php

namespace UnitTests\Service;

use App\Dto\ManageTaskPointDto;
use App\Entity\User;
use App\Manager\TaskManager;
use App\Manager\UserManager;
use App\Manager\UserPointManager;
use App\Service\PointsService;
use UnitTests\FixturedTestCase;
use UnitTests\Fixtures\MultipleCoursesFixture;
use UnitTests\Fixtures\MultipleLessonsFixture;
use UnitTests\Fixtures\MultipleSkillsFixture;
use UnitTests\Fixtures\MultipleTasksFixture;
use UnitTests\Fixtures\MultipleTaskSkillProportionsFixture;
use UnitTests\Fixtures\MultipleUsersFixture;

class PutPointsTest extends FixturedTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->addFixture(new MultipleCoursesFixture());
        $this->addFixture(new MultipleLessonsFixture());
        $this->addFixture(new MultipleTasksFixture());
        $this->addFixture(new MultipleSkillsFixture());
        $this->addFixture(new MultipleTaskSkillProportionsFixture());
        $this->addFixture(new MultipleUsersFixture());
        $this->executeFixtures();
    }

    public function testDoublePut()
    {
        /** @var PointsService $pointsService */
        $pointsService = self::getContainer()->get(PointsService::class);
        /** @var UserManager $userManager */
        $userManager = self::getContainer()->get(UserManager::class);
        $user = $userManager->getUserByPhone(MultipleUsersFixture::DAFAULT_USER_PHONE);
        /** @var TaskManager $taskManager */
        $taskManager = self::getContainer()->get(TaskManager::class);
        $task = $taskManager->getTaskByTitle(MultipleTasksFixture::TASK);
        $dto = new ManageTaskPointDto(
            taskId: $task->getId(),
            points: 10,
        );


        $pointsService->putByDto($user, $dto);
        $pointsService->putByDto($user, $dto);
        $pointsService->putByDto($user, $dto);
        /** @var UserPointManager $userPointManager */
        $userPointManager = self::getContainer()->get(UserPointManager::class);
        $result = $userPointManager->getPoints($user->getId(), $task->getId());


        self::assertEquals(10, $result);
    }
}
