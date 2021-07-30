<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $basket = $request->getSession()->get('listBasket');
   
        if (!$basket) {
            $request->getSession()->set('listBasket', []);
        };
        if ($request->request->get('product')) {
            if (!isset($basket[$request->request->get('product')])) {
                $basket[$request->request->get('product')] = [
                    'product' => $productRepository->findOneBy(
                        ['id'=> $request->request->get('product')]
                    ),
                    'quantity' => $request->request->get('quantity')
                ];
            } else {
                $basket[$request->request->get('product')]['quantity'] = 
                $basket[$request->request->get('product')]['quantity'] + 
                $request->request->get('quantity');
            };
        }
        
        $request->getSession()->set('listBasket', $basket);

        $form = $this->createForm(UserAddressListType::class, [], [
            'action' => $this->generateUrl('invoice_index')
        ]);
        $form->handleRequest($request);
        
        return $this->render('basket/index.html.twig', [
            'formUserAddress' => $form->createView(),
            'listBasket' => $basket
        ]);
    }
}
