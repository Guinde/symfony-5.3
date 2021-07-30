<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Facture;
use App\Entity\FactureLine;
use App\Repository\FactureRepository;
use Knp\Snappy\Pdf;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture", name="facture")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $basket = $request->getSession()->get('listBasket');

        $facture = new Facture();

        $facture->setUser($this->getUser());
        $facture->setDate(new \DateTime());

        $totalFacture;

        foreach ($basket as $item) {
            $factureLine = new FactureLine();
            $factureLine->setFacture($facture);
            $factureLine->setProductId($item['product']->getId());
            $factureLine->setProductPrice($item['product']->getPrice());
            $factureLine->setProductName($item['product']->getName());
            $factureLine->setQuantity($item['quantity']);

            $price = $factureLine->getProductPrice();
            $quantity = $factureLine->getQuantity();

            $total = $price * $quantity;

            $totalFacture = $totalFacture + $total;

            $factureLine->setTotal($total);
    
            $entityManager->persist($factureLine);
        };

        $facture->setTotal($totalFacture);

        $entityManager->persist($facture);
        
        $entityManager->flush();

        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }

    /**
     * @return Response
     * @Route("/facture", name="list")
     */
    public function listByUser(FactureRepository $factureRepo)
    {
        return $this->render('facture/list.html.twig', [
            'factures' => $factureRepo->findBy(['user' => $this->getUser()])
        ]);
    }

    /**
     * @param Facture $facture
     * @return Response
     * @Route("/{facture}", name="_show")
     */
    public function show(Facture $invoice)
    {
        return $this->render('facture/index.html.twig', [
            'invoice' => $invoice
        ]);
    }

    /**
     * @Route("/{facture}/download", name="_download")
     */
    public function download(Facture $invoice, Pdf $pdf, Filesysteme $fileSysteme)
    {
        $fileName = $_ENV['FACTURE_FOLDER'] . 'Facture ' . $facture->getId() . '.pdf';

        if (!$fileSysteme->exists($fileName)) {
            $html = $this->renderView('facture/index.pdf.html.twig', [
                'invoice' => $invoice
            ]);
    
            $content = $pdf->getOutputFromHtml($html);
    
            $fileSysteme->dumpFile($fileName, $content);
        }

        $file = new File($fileName);

        return $this->file($file);
    }
}
