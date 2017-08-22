<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 10:04
 */

namespace LouvreBundle\Controller;

use LouvreBundle\Entity\Order;
use LouvreBundle\Entity\Item;
use LouvreBundle\Entity\Ticket;
use LouvreBundle\Form\OrderType;
use LouvreBundle\Repository\ItemRepository;
use function PHPSTORM_META\type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Charge;
use Stripe\Error\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;

class TicketController extends Controller
{
    /**
     * @Route("/billetterie", name="create_ticket")
     */
    public function createAction(Request $request)
    {
        $order = new Order();
        //generate form
        $form = $this->createForm(OrderType::class, $order);
        //check form submission
        if ($request->isMethod('POST')) {
            //link $order with data typed in the form
            $form->handleRequest($request);
            //vérification des infos
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                //relate the items to the order and get the ticket
                foreach ($order->getItems() as $item) {
                    $order->addItem($item);
                    $em->persist($item);
                    $item->setTicket($em->getRepository('LouvreBundle:Ticket')->getTicketByRateAndDuration($item->getTicketrate(), $order->getDuration())[0]);
                }
                $em->persist($order);
                $em->flush();
                //redirect to paiement page
                return $this->redirectToRoute('checkout', ['id' => $order->getId()]);
            }
        }
        return $this->render('LouvreBundle:ticket:create.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/checkout/{id}", name="checkout")
     */
    public function checkoutAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //retrieving the order
        $order = $em->getRepository('LouvreBundle:Order')->find($id);
        //getting the items in the order
        $lines = $em->getRepository('LouvreBundle:Item')->getItemsDataFromOrder($order);
        //calculate total price
        $total_price = 0;
        $stripe_amount = 0;
        foreach ($lines as $line) {
            $total_price += $line['total_price'];
            //stripe need the price in cents
            $stripe_amount = $total_price * 100;
        }
        return $this->render('LouvreBundle:ticket:checkout.html.twig', [
            'order' => $order,
            'lines' => $lines,
            'stripe_amount' => $stripe_amount,
            'total_price' => $total_price
        ]);
    }

    /**
     * @Route("/paiement/{id}", name="paiement")
     */
    public function payAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //retrieving the order
        $order = $em->getRepository('LouvreBundle:Order')->find($id);
        //Get the whole amount of the order
        $amount = $em->getRepository('LouvreBundle:Item')->getTotalAmountOfOrder($order);
        $stripe_amount = $amount['total_price'] * 100;
        //secret key for the api
        Stripe::setApiKey("sk_test_mpNlXQIdKANa8PC3wpNdhwo6");
        //recover the token created by checkout page
        $token = $_POST['stripeToken'];
        //create the charge to get paiement from user
        try {
            $charge = Charge::create([
                "amount" => $stripe_amount,
                "currency" => "eur",
                "description" => "Commande " . $order->getOrderNumber(),
                "source" => $token
            ]);
            $this->addFlash("success","Votre paiement a bien été enregistré.");
            return $this->redirectToRoute('finalise', ['id' => $order->getId()]);
        }catch (Card $e){
            $this->addFlash("error", "Une erreur s'est produite. Veuillez recommencer.");
            //redirect to paiement page
            return $this->redirectToRoute('checkout', ['id' => $order->getId()]);
        }
    }

    /**
     * @Route("/confirm/{id}", name="finalise")
     */
    public function finaliseAction($id)
    {
        return $this->render('LouvreBundle:page:index.html.twig');
    }
}