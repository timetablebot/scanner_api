<?php

namespace App\Controller;

use App\Entity\CafeteriaMeal;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PushController extends AbstractController
{
    /**
     * @Route("/push", name="push", methods={"POST"})
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function index(Request $request, LoggerInterface $logger)
    {
        $requestKey = $request->query->get('key');
        $givenKey = getenv('PUSH_KEY') ?: '~';

        if ($givenKey == '~') {
            return $this->json([
                'message' => 'Please set the PUSH_KEY in your .env.local file, otherwise it is an security risk!'
            ], RESPONSE::HTTP_NOT_IMPLEMENTED);
        }

        if ($requestKey == null) {
            return $this->json([
                'message' => 'No key is set!'
            ], RESPONSE::HTTP_UNAUTHORIZED);
        } else if ($requestKey !== $givenKey) {
            return $this->json([
                'message' => 'Wrong key is set!'
            ], RESPONSE::HTTP_UNAUTHORIZED);
        }

        $entityManger = $this->getDoctrine()->getManager();
        $mealRepository = $this->getDoctrine()->getRepository(CafeteriaMeal::class);
        // TODO: Prefetch the meals for the days

        $json = json_decode($request->getContent(), true);

        if (!is_array($json)) {
            return $this->error('Why there\'s no JSON?', [], []);
        }

        $logger->info('Got request with '.count($json).' meals');

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

            $logger->debug(json_encode($item));

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

        $veg = $meal->getVegetarian() ? 'vegetarian' : 'meat';

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
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/push/test", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function test(Request $request)
    {
        $requestKey = $request->query->get('key');
        $givenKey = getenv('PUSH_KEY') ?: '~';

        if ($givenKey == '~') {
            return new Response(
                'Please set the PUSH_KEY in your .env.local file, otherwise it is an security risk!',
                RESPONSE::HTTP_NOT_IMPLEMENTED);
        }

        if ($requestKey == $givenKey) {
            return new Response('', Response::HTTP_OK);
        } else {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }
    }
}
