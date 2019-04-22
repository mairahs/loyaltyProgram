<?php

namespace App\Controller;

use App\Entity\Period;
use App\Entity\Product;
use App\Entity\PurchaseDate;
use App\Repository\PeriodRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseDateRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/loyalty", name="loyalty_index")
     */
    public function index(ObjectManager $manager, PeriodRepository $repositoryPeriod, ProductRepository $repositoryProduct, PurchaseDateRepository $repositoryPurchaseDate)
    { 
        $period1       = $repositoryPeriod->find(1);
        $purchaseDate1 = $repositoryPurchaseDate->find(1);
        $period2       = $repositoryPeriod->find(2);
        $purchaseDate2 = $repositoryPurchaseDate->find(6);
        $period3       = $repositoryPeriod->find(3);
        $purchaseDate3 = $repositoryPurchaseDate->find(7);

       
        $allPoints1 = $period1->getAllPoints($purchaseDate1, $repositoryProduct);
        $money1 = $allPoints1 * 0.001;

        $allPoints2 = $period2->getAllPoints($purchaseDate2, $repositoryProduct);
        $money2 = $allPoints2 * 0.001;

        $allPoints3 = $period3->getAllPoints($purchaseDate3, $repositoryProduct);
        $money3 = $allPoints3 * 0.001;
    
        return $this->render('product/index.html.twig', [
             'allPoints1' => $allPoints1,
             'money1'     => $money1,
             'allPoints2' => $allPoints2,
             'money2'     => $money2,
             'allPoints3' => $allPoints3,
             'money3'     => $money3
         ]);
    }
}
