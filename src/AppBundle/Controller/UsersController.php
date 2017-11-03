<?php

//src/AppBundle/Controller/UsersController.php
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

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UsersController extends Controller
{

    /**
     * @Route("/adduser",name="adduser")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function loadResidentAddForm(Request $request)
    {   
        $_SESSION['error'] = null;
        if($request->request->has('usuario') && $request->request->has('mail') &&
            $request->request->has('secondmail') && $request->request->has('pass') &&  
            $request->request->has('secondpass') && $request->request->has('rol'))
        {
            $username = strtolower($request->request->get('usuario'));
            $password =$request->request->get('pass');
            $checkpassword = $request->request->get('secondpass');
            $mail = strtolower($request->request->get('mail'));
            $checkmail = strtolower($request->request->get('secondmail'));
            $rol = $request->request->get('rol');
            if($this->AddAppUser($username, $password, $checkpassword,$mail, $checkmail, $rol))
            {
                $encoder = $this->get('security.encoder_factory')->getEncoder('AppBundle\Entity\User');
                $encodedPassword = $encoder->encodePassword($password,null);
                dump($password, $encodedPassword);
                $this->Insert($username, $encodedPassword, $mail, $rol);
                return $this->render('vistas_test/exito.html.twig',
                array('var'=>'Usuario Ingresado'));
            }
        }
        return $this->render('vistas_test/formadduser.html.twig',
        array('error'=>$_SESSION['error']));
    }
    //Chequear 
    private function AddAppUser($username, $password, $checkpassword, $email, $checkmail, $rol)
    {
        //Longitud usuario
        if(strlen($username)<=6)
        {
            $_SESSION['error'] = "Usuario debe ser mayor a 6 caracteres";
            return false;
        }
        //Match en pass
        if($password != $checkpassword)
        {
            $_SESSION['error'] = "Contraseñas no concuerdan";
            return false;
        }
        //Match en mail
        if($email != $checkmail)
        {
            $_SESSION['error'] = "Emails no concuerdan";
            return false;
        }
        //Mail Correcto
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email)<10) 
        {
            $_SESSION['error'] = "Email Incorrecto";
            return false;
        }
        //Fuerza de Contraseña
        return $this->StrenghtPass($password);

    }
    //Contraseña Segura
    private function StrenghtPass($pass)
    {
        if (strlen($pass) < 8) {
            $_SESSION['error'] = "Contraseña debe ser de 8 caracteres mínimo";
            return false;
        }
    
        if (!preg_match("#[0-9]+#", $pass)) {
            $_SESSION['error'] = "Contraseña debe contener por lo menos un número";
            return false;
        }
    
        if (!preg_match("#[a-zA-Z]+#", $pass)) {
            $_SESSION['error'] = "Contraseña debe contener por lo menos una letra";
            return false;
        }     
        return true;
    }
    //Insert
    public function Insert($username, $pass, $mail, $rol)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();
        
        $user = new User();
        $user->constructor($username, $pass, $mail, $rol);
    
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);
    
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }
}