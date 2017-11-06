<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class DashboardController extends Controller
{
    public function loadDash()
    {
        return $this->render('vistas/dashboard.html.twig',
        array ('username' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
        'role' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
        'formuser' => 'Usuario Ingresado'
        ));
    }
}