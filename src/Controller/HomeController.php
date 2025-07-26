<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Restaurant;
use Symfony\Bundle\SecurityBundle\Security;
use OpenApi\Attributes as OA;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    #[Route('/newrestaurant', name: 'api_new_restaurant', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crea un nuevo restaurante',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name', 'address', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'address', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Restaurante creado con éxito',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean')
                    ]
                )
            )
        ]
    )]
    public function addRestaurant(Request $request, EntityManagerInterface $entityManager, Security $security)
    {
        $data = json_decode($request->getContent(), true);

        $user = $security->getUser();

        $restaurant = new Restaurant();
        $restaurant->setName($data['name']);
        $restaurant->setAddress($data['address']);
        $restaurant->setPhone($data['phone']);
        $restaurant->setUser($user);

        $entityManager->persist($restaurant);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/showrestaurantdata', name: 'api_show_restaurant_data', methods: ['POST'])]
    #[OA\Post(
        summary: 'Obtiene los datos de un restaurante',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Datos del restaurante',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'address', type: 'string'),
                        new OA\Property(property: 'phone', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function showRestaurantData(Request $request, RestaurantRepository $restaurantRepository)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $restaurant = $restaurantRepository->find($id);

        return new JsonResponse([
            'name' => $restaurant->getName(),
            'address' => $restaurant->getAddress(),
            'phone' =>$restaurant->getPhone(),
        ]);
    }

    #[Route('/updaterestaurant', name: 'api_update_restaurant', methods: ['POST'])]
    #[OA\Post(
        summary: 'Actualiza los datos de un restaurante',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['id', 'name', 'address', 'phone'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'address', type: 'string'),
                    new OA\Property(property: 'phone', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Restaurante actualizado',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean')
                    ]
                )
            )
        ]
    )]
    public function updateRestaurant(Request $request, RestaurantRepository $restaurantRepository, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        
        $restaurant = $restaurantRepository->find($id);

        $restaurant->setName($data['name']);
        $restaurant->setAddress($data['address']);
        $restaurant->setPhone($data['phone']);

        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/eraserestaurant', name: 'api_erase_restaurant', methods: ['POST'])]
    #[OA\Post(
        summary: 'Elimina un restaurante',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Restaurante eliminado',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean')
                    ]
                )
            )
        ]
    )]
    public function eraseRestaurant(Request $request, RestaurantRepository $restaurantRepository, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $restaurant = $restaurantRepository->find($id);

        $entityManager->remove($restaurant);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
