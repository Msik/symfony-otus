<?php

namespace App\Controller;

use App\Dto\ManageUserPointDto;
use App\Entity\UserPoint;
use App\Form\Type\UserPointType;
use App\Manager\UserManager;
use App\Manager\UserPointManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPointController extends AbstractController
{
    public function __construct(
        private readonly UserPointManager $userPointManager,
        private readonly UserManager $userManager,
        private readonly FormFactoryInterface $formFactory,
    ) {}

    #[Route(path: '/store-user-point', name: 'create_user', methods: ['GET', 'POST'])]
    #[Route(path: '/update-user-point/{id}', name: 'update-user', methods: ['GET', 'POST'])]
    public function manageUserPointAction(Request $request, string $_route, ?int $id = null): Response
    {
        $dto = null;
        if ($id) {
            $userPoint = $this->userPointManager->getUserPointById($id);
            $dto = ManageUserPointDto::fromEntity($userPoint);
        }

        $form = $this->formFactory->create(UserPointType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ManageUserPointDto $manageUserDto */
            $manageUserDto = $form->getData();
            $this->userPointManager->savePointByDto($userPoint ?? new UserPoint(), $manageUserDto);
        }

        return $this->renderForm('manageUserPoint.html.twig', [
            'form' => $form,
            'userPoint' => $dto,
        ]);
    }
}
