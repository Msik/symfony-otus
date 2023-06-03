<?php

namespace UnitTests\Service;

use App\Entity\Skill;
use App\Entity\TaskSkillProportion;
use App\Manager\TaskManager;
use App\Service\PointsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PointsServiceTest extends TestCase
{
    public function proportionsDataProvider(): iterable
    {
        yield 'empty proportions' => [
            new ArrayCollection(),
            10,
            [['skill' => null, 'points' => 10]]
        ];


        $proportions = new ArrayCollection();

        $proportion = new TaskSkillProportion();
        $skill1 = new Skill();
        $skill1->setId(1);
        $proportion->setSkill($skill1);
        $proportion->setProportion(80);
        $proportions->add($proportion);

        $proportion = new TaskSkillProportion();
        $skill2 = new Skill();
        $skill2->setId(2);
        $proportion->setSkill($skill2);
        $proportion->setProportion(20);
        $proportions->add($proportion);

        yield 'full filled points' => [
            $proportions,
            9,
            [
                ['skill' => $skill1, 'points' => 7],
                ['skill' => $skill2, 'points' => 2],
            ]
        ];


        $proportions = new ArrayCollection();

        $proportion = new TaskSkillProportion();
        $skill1 = new Skill();
        $skill1->setId(1);
        $proportion->setSkill($skill1);
        $proportion->setProportion(70);
        $proportions->add($proportion);

        $proportion = new TaskSkillProportion();
        $skill2 = new Skill();
        $skill2->setId(2);
        $proportion->setSkill($skill2);
        $proportion->setProportion(20);
        $proportions->add($proportion);

        yield 'with remain' => [
            $proportions,
            9,
            [
                ['skill' => $skill1, 'points' => 6],
                ['skill' => $skill2, 'points' => 2],
                ['skill' => null, 'points' => 1],
            ]
        ];
    }

    /**
     * @dataProvider proportionsDataProvider
     */
    public function testGetPointsByProportion(Collection $proportions, int $points, array $expected): void
    {
        $pointsService = new PointsService($this->createMock(TaskManager::class), $this->createMock(EntityManagerInterface::class));

        $result = $pointsService->getPointsByProportion($proportions, $points);

        static::assertEquals($expected, $result);
    }
}
