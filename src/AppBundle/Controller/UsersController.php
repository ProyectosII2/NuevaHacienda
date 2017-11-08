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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

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
                try
                {
                    $this->Insert($username, $encodedPassword, $mail, $rol);
                } 
                catch(Exception $ex)
                {
                    dump($ex->getMessage());
                }
                return $this->render('vistas/dashboard.html.twig',
                array ('username' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
                'role' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
                'message' => 'Usuario Ingresado'
                ));
            }
        }
        return $this->render('vistas/registro.html.twig',
        array('error'=>$_SESSION['error']));
    }
    
    /**
     * @Route("/allusers",name="allusers")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function formGetAll(Request $request)
    {
        dump($this->Get_All());   

        return $this->render('vistas/tablaUsuarios.html.twig',
        array('error'=>$_SESSION['error'], 'usuarios'=>$this->Get_ALL()
    ));
    }
    /**
     * @Route("/updateuser/{username}", name="updateuser")
     * @Security("has_role('ROLE_ADMIN')") 
     */
    public function loadupdateUser(Request $request, $username)
    {
        $temp = $this->Get_by_User($username);
        return $this->render('vistas_test/updateuser.html.twig',
        array('username'=>$username,
        'name'=>$temp[0]['username'],
        'mail'=>$temp[0]['email'],
        'rol'=>$temp[0]['role'], 
        'active'=>$temp[0]['isActive'],
        'error'=>""));
     }
     /**
     * @Route("/checkupdate", name="checkupdate")
     * @Security("has_role('ROLE_ADMIN')") 
     */
    public function checkupdate(Request $request)
    { 
        $_SESSION['error'] = "";
        if($request->request->has('newusername') && 
        $request->request->has('oldusername') && 
        $request->request->has('mail') && 
        $request->request->has('mailcheck') && 
        $request->request->has('role'))
        {
            //campos
            $olduser = strtolower($request->request->get('oldusername'));
            $newuser = strtolower($request->request->get('newusername'));
            $mail = strtolower($request->request->get('mail'));
            $mailcheck = strtolower($request->request->get('mailcheck'));
            $role = $request->request->get('role');
            $active = false;
            if($request->request->has('habilitado'))
            {
                $active = true;
            }
            //Validar
            //dump($olduser, $newuser, $mail, $mailcheck, $role, $active);
            
            $res = "Falló";
            return $this->render('vistas_test/exito.html.twig',
            array ('var'=>$res));
        }
        else
        {
            //No hay campos
            $_SESSION['error'] = "Llenar todos los campos";
            $temp = $this->Get_by_User($username);
            return $this->render('vistas_test/updateuser.html.twig',
            array('username'=>$username,
            'name'=>$temp[0]['username'],
            'mail'=>$temp[0]['email'],
            'rol'=>$temp[0]['role'], 
            'active'=>$temp[0]['isActive'],
            'error'=>$_SESSION['error']));
        }
    }
    //Get_by_username 
    private function Get_by_User($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->createQuery('SELECT u.username, u.email, u.role, u.isActive FROM AppBundle\Entity\User u 
                                WHERE u.username = :username')
        ->setParameter('username', $username);
        return $user->getResult();
    
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
        //Existe Usuario
        if($this->Exist($username))
        {
            $_SESSION['error'] = "Usuario ya existe";
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
    ///Querys-----------------------------------------------------
    //Insert
    private function Insert($username, $pass, $mail, $rol)
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
    //Get all
    private function Get_All()
    {
        /*$result = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $result;
         */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u.username, u.email, u.role, u.isActive FROM AppBundle\Entity\User u');
        $users = $query->getResult();
        return $users;
    
    }
    //Check if User Exist
    private function Exist($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->createQuery('SELECT u.username FROM AppBundle\Entity\User u 
                                WHERE u.username = :username')
        ->setParameter('username', $username);
        if(empty($user->getResult())){ return false;}
        return true;
    }
    
}