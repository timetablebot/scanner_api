<?php

namespace App\Controller;

use App\Entity\CafeteriaMeal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PushController extends AbstractController
{
    /**
     * @Route("/push", name="push", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Request $request)
    {
        $key = $request->query->get('key');

        if ($key == null) {
            return $this->json([
                'message' => 'No key is set!'
            ], 401);
        } else if ($key !== 'ynes4uüz9rpawe4h8üj0z4ü0äop9zyeuzü') {
            return $this->json([
                'message' => 'Wrong key is set!'
            ], 401);
        }

        $entityManger = $this->getDoctrine()->getManager();
        $mealRepository = $this->getDoctrine()->getRepository(CafeteriaMeal::class);
        // TODO: Preftech the meals for the days

        $json = json_decode($request->getContent(), true);

        if (!is_array($json)) {
            return $this->error('Why there\'s no JSON?', [], []);
        }

        $changes = [];

        foreach ($json as $key => $item) {

            if (!is_array($item)) {
                return $this->error('This thing has to be an object', $json, $item);
            }

            $day = $item['day'];
            $vegetarian = $item['vegetarian'];
            $price = $item['price'];
            $description = $item['description'];

            if ($day === null || $vegetarian === null || $price === null || $description === null) {
                // TODO: Log this
                return $this->error('Something is null', $json, $item);
            }

            if (!is_numeric($day) || !is_numeric($price)) {
                return $this->error('Something isn\'t a number!', $json, $item);
            }

            if (!is_bool($vegetarian)) {
                return $this->error('Vegetarian should be a boolean!', $json, $item);
            }

            $meal = $mealRepository->findOneBy([
                'day' => $day,
                'vegetarian' => $vegetarian
            ]);

            $creating = $meal === null;

            // Updating the existing meal, otherwise create a new one
            if ($creating) {
                $meal = new CafeteriaMeal();
            }

            $meal->setDay($day);
            $meal->setVegetarian($vegetarian);
            $meal->setPrice($price);
            $meal->setMeal($description);

            $entityManger->persist($meal);

            // Tracking whether it was created or updated
            $this->track($meal, $creating, $changes);
        }

        $entityManger->flush();

        return $this->json([
            'message' => 'Saved!',
            'changes' => $changes
        ]);
    }

    private function track(CafeteriaMeal $meal, bool $created, array &$tracking)
    {
        if (!isset($tracking[$meal->getDay()])) {
            $tracking[$meal->getDay()] = [];
        }

        $veg = $meal->getVegetarian() ? 'vegetarian' : 'meat_eater';

        $tracking[$meal->getDay()][$veg] = $created;
    }

    private function error(string $message, array $json, $invalidJson): JsonResponse
    {
        return $this->json([
            'message' => $message,
            'extra' => [
                'full' => $json,
                'invalid' => $invalidJson
            ]
        ], 400);
    }
}
