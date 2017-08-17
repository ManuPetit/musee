<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:04
 */

namespace LouvreBundle\Controller;

use LouvreBundle\Entity\Order;
use LouvreBundle\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TicketController extends Controller
{
    /**
     * @Route("/billetterie", name="ticket_page")
     */
    public function indexAction(Request $request)
    {
        $order = new Order();
        //generate form
        $form = $this->createForm(OrderType::class, $order);

        //check form submission
        if ($request->isMethod('POST')){
            //link $order with data typed in the form
            $form->handleRequest($request);
            //vérification des infos
            if ($form->isValid())
            {
                //save our data
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
            }
        }
        return $this->render('LouvreBundle:ticket:index.html.twig',[
            'myForm' => $form->createView()
        ]);
    }
}