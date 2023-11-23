<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Owner;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/car')]
class CarController extends AbstractController
{  
    #[Route('/', name: 'create_new_car', methods: ['POST'])]
    public function newStudent(EntityManagerInterface $em, Request $request): JsonResponse  // EntityManagerInterface -> faz tudo
    {
        $parameters = json_decode($request->getContent(), true); 

        $ownerRepository = $em->getRepository(Owner::class);
        $owner = $ownerRepository->find($parameters['owner']);
        
        if (is_null ($owner)) {
            throw $this->createNotFoundException('the owner is not exist.');
        }

        $car = new Car();
        $car->setModel($parameters["model"]);
        $car->setPlate($parameters["plate"]);
        $car->setOwner($owner);

        $em->persist($car);
        $em->flush();

        return $this->json("car saved");
    }

    #[Route('/', name: 'get_all_', methods: ['GET'])]
    public function index(CarRepository $carRepository): JsonResponse
    {
        $cars = $carRepository->findAll();

        return $this->json($cars);
    }

    #[Route('/{id}', name: 'edit_car', methods: ['PUT'])]
    public function editStudent(EntityManagerInterface $em, Request $request, int $id): JsonResponse
    {
        $carRepository = $em->getRepository(Car::class);
        $car = $carRepository->find($id);

        $parameters = json_decode($request->getContent(), true); 

        $car->setModel($parameters["model"]);
        $car->setPlate($parameters["plate"]);

        $em->persist($car);
        $em->flush();

        return $this->json("car updated");
    }
    
    #[Route('/{id}', name: 'delete_car', methods:["DELETE"])]
    public function deleteStudent(EntityManagerInterface $em, int $id): JsonResponse
    {
        $carRepository = $em->getRepository(Car::class);
        $car = $carRepository->find($id);

        if (is_null($car)) {
            return $this->json("car already deleted");
        }

        $em->remove($car);
        $em->flush();

        return $this->json("car removed");
    }
}