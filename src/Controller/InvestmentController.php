<?php

namespace App\Controller;

use App\DTO\InvestmentDTO;
use App\Entity\Investment;
use App\Service\InvestmentService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/investment', name: 'app_investment_')]
class InvestmentController extends AbstractController
{
    public function __construct(
        private readonly InvestmentService $investmentService,
        private readonly UserService    $userService
    )
    {
        // noop
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: 'investment_create')]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        // Decode the JSON content and create our Investment DTO
        $investmentDTO = InvestmentDTO::fromRequest($request);

        // Validate the DTO
        $violations = $validator->validate($investmentDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $currentUser = $this->userService->getByToken($request->headers->get('Authorization'));
        $investment     = $this->investmentService->create($investmentDTO, $currentUser);

        return $this->json($investment);
    }

    #[Route('', name: 'read_all', methods: ['GET', 'OPTIONS'])]
    public function readAll(Request $request): Response
    {
        $currentUser    = $this->userService->getByToken($request->headers->get('Authorization'));
        $excludeDeleted = $request->query->getBoolean('excludeDeleted', true);

        return $this->json($this->investmentService->getAllInvestmentsByOwner($currentUser, $excludeDeleted));
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Investment $investment): Response
    {
        return $this->json($investment);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[IsGranted(attribute: 'investment_update', subject: 'investment')]
    public function update(Request $request, ValidatorInterface $validator, Investment $investment): Response
    {
        // Decode the JSON content and create our Investment DTO
        $investmentDTO = InvestmentDTO::fromRequest($request);

        // Validate the DTO
        $violations = $validator->validate($investmentDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Pass the DTO to the service
        $this->investmentService->update($investment, $investmentDTO);

        return $this->json($investment);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted(attribute: 'investment_delete', subject: 'investment')]
    public function delete(Investment $investment): Response
    {
        $this->investmentService->delete($investment);

        return $this->json([]);
    }
}
