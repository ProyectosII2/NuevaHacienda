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
    public function loadUserAddForm(Request $request)
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
                'message' => null,
                'residences' => null,
                'residents' => null,
                'payments' => null, ));
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
        dump($username);
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
        
        $olduser = strtolower($request->request->get('oldusername'));
        $_SESSION['error'] = "";
        if($request->request->has('newusername') && 
        $request->request->has('mail') && 
        $request->request->has('mailcheck') && 
        $request->request->has('role'))
        {
            //campos
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
            if($this->ValidUpdate($olduser, $newuser, $mail, $mailcheck))
            {
                //Hacer Update
                $this->UpdateUser($olduser, $newuser, $mail, $role, $active);
                return $this->render('vistas/dashboard.html.twig',
                array ('username' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
                'role' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
                'message' => null,
                'residences' => null,
                'residents' => null,
                'payments' => null,
            ));
            }
        }
        else
        {
            $_SESSION['error']="Llenar todos los campos";
        }
        //No hay campos
        $temp = $this->Get_by_User($olduser);
        return $this->render('vistas_test/updateuser.html.twig',
        array('username'=>$olduser,
        'name'=>$temp[0]['username'],
        'mail'=>$temp[0]['email'],
        'rol'=>$temp[0]['role'], 
        'active'=>$temp[0]['isActive'],
        'error'=>$_SESSION['error']));
    }
    //Validar Update
    private function ValidUpdate($oldusername, $newuser, $mail, $mailcheck)
    {
        //Longitud usuario
        if(strlen($newuser)<=6)
        {
            $_SESSION['error'] = "Usuario debe ser mayor a 6 caracteres";
            return false;
        }
        //Existe Usuario
        if($newuser!=$oldusername)
        {
            if($this->Exist($newuser))
            {
                $_SESSION['error'] = "Usuario ya existe";
                return false;
            }
        }
        //Match en mail
        if($mail != $mailcheck)
        {
            $_SESSION['error'] = "Emails no concuerdan";
            return false;
        }
        //Mail Correcto
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mail)<10) 
        {
            $_SESSION['error'] = "Email Incorrecto";
            return false;
        }
        return true;
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
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u.username, u.email, u.role, u.isActive FROM AppBundle\Entity\User u');
        $users = $query->getResult();
        return $users;
    
    }
    //Check if User Exist
    private function Exist($username)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u.username FROM AppBundle\Entity\User u 
                                WHERE u.username = :username')
        ->setParameter('username', $username);
        if(empty($query->getResult())){ return false;}
        return true;
    }
    //Update
    private function UpdateUser($olduser, $newuser, $mail, $rol, $active)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT u.id FROM AppBundle\Entity\User u WHERE u.username = :username')
        ->setParameter('username', $olduser);
        $result= $query->getResult()[0];

        $user = $em->getRepository(User::class)->find($result);
    
        $user->setUsername($newuser);
        $user->setEmail($mail);
        $user->setRol($rol);
        $user->setActive($active);
        $em->flush();
    }
    
}