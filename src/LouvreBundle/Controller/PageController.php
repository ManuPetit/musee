<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 16/08/2017
 * Time: 09:42
 */

namespace LouvreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction()
    {
        return $this->render('LouvreBundle:page:index.html.twig');
    }

    /**
     * @Route("/infos", name="info_page")
     */
    public function infoAction()
    {
        return $this->render('LouvreBundle:page:info.html.twig');
    }
}