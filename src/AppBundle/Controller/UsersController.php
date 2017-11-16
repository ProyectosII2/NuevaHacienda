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
     * Recibe POST con los parmametros para agregar usuario
     */
    public function loadUserAddForm(Request $request)
    {   
        $_SESSION['error']=null;
        //Revisa si no falta algun parametro
        if($request->request->has('usuario') && $request->request->has('mail') &&
            $request->request->has('secondmail') && $request->request->has('pass') &&  
            $request->request->has('secondpass') && $request->request->has('rol'))
        {
            //Pasar todo a minuscula a variables
            $username = strtolower($request->request->get('usuario'));
            $password =$request->request->get('pass'); //pass en plaintext
            $checkpassword = $request->request->get('secondpass');
            $mail = strtolower($request->request->get('mail'));
            $checkmail = strtolower($request->request->get('secondmail'));
            $rol = $request->request->get('rol'); //Rol es ROLE_USER o ROLE_ADMIN
            //Funcion para validar parametros
            if($this->UserChecker($username, $password, $checkpassword,$mail, $checkmail, $rol))
            {
                $message = "Fallo en ingreso a la base de datos";
                try
                {
                    $this->getDoctrine()->getManager()->getRepository(User::class)->InsertUser(
                        $username, 
                        $this->EncodePassword($password),
                        $mail, 
                        $rol);
                    $message = "Se ingresó con exíto";
                } 
                catch(\Exception $ex)
                {
                    dump($ex);
                    //retorna la vista
                    return $this->render('vistas/registro.html.twig',
                    array('error'=>$message));
                }
                //retorna Dashboard con menssage
                return $this->forward('AppBundle\Controller\DashboardController::loaddash', 
                array("message"=>$message));
            }
        }
        //Retorna vista original con mensaje de error
        return $this->render('vistas/registro.html.twig',
        array('error'=>$_SESSION['error']));
    }
    /**
     * @Route("/allusers",name="allusers")
     * @Security("has_role('ROLE_ADMIN')") 
     * Renderiza array de usuarios
     */
    public function loadAllUsersForm(Request $request)
    {
        $usuarios = $this->getDoctrine()->getManager()->getRepository(User::class)->GetAll();
        return $this->render('vistas/tablaUsuarios.html.twig',
        array('error'=>$_SESSION['error'], 'usuarios'=>$usuarios
        ));
    
    }
    /**
     * @Route("/updateuser/{username}", name="updateuser")
     * @Security("has_role('ROLE_ADMIN')")
     * Obtiene usuario del parametro y renderiza la vista para editar
     */
    public function loadUpdateUserForm(Request $request, $username)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->Get_by_User($username);
        return $this->render('vistas/updateuser.html.twig', 
        array('username'=>$username,
        'name'=>$user->getUsername(),
        'mail'=>$user->getEmail(),
        'rol'=>$user->getRoles(), 
        'active'=>$user->getActive(),
        'error'=>""));
     }

     /**
     * @Route("/checkupdate", name="checkupdate")
     * @Security("has_role('ROLE_ADMIN')") 
     * Check User new data and update
     */
   
    public function checkupdate(Request $request)
    { 
        
        $olduser = strtolower($request->request->get('oldusername'));
        $_SESSION['error'] = "";
        //check if there are parameters
        if($request->request->has('newusername') && 
        $request->request->has('mail') && 
        $request->request->has('mailcheck') && 
        $request->request->has('role'))
        {
            //coloca campos a minuscula
            $newuser = strtolower($request->request->get('newusername'));
            $mail = strtolower($request->request->get('mail'));
            $mailcheck = strtolower($request->request->get('mailcheck'));
            $role = $request->request->get('role'); //Role puede ser ROLE_USER y ROLE_ADMIN
            $active = false;
            if($request->request->has('habilitado'))
            {
                $active = true;
            }
            //Validar
            if($this->ValidUpdate($olduser, $newuser, $mail, $mailcheck))
            {
                //Hacer Update
                $this->UpdateUser($olduser, $newuser, $mail, $role, $active);
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array("message"=>"Actualización exitosa"));
            }
        }
        else
        {
            $_SESSION['error']="Llenar todos los campos";
        }
        //No hay campos
        $oldus = $this->getDoctrine()->getManager()->getRepository(User::class)->Get_by_User($olduser);
        dump($olduser,$oldus);
        return $this->render('vistas/updateuser.html.twig',
        array('username'=>$olduser,
        'name'=>$oldus->getUsername(),
        'mail'=>$oldus->getEmail(),
        'rol'=>$oldus->getRoles(), 
        'active'=>$oldus->getActive(),
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
    //-----------------------------------FUNCIONES DE APOYO------------------------------
    /**
     * Chequear que los parametros estén correctos
     * Retorna True si no hay problema
     * Retorna False si hay problema y error en variable error de session
     */
    private function UserChecker($username, $password, $checkpassword, $email, $checkmail, $rol)
    {
        //Longitud usuario no menor a 6
        if(strlen($username)<=6)
        {
            $_SESSION['error'] = "Usuario debe ser mayor a 6 caracteres";
            return false;
        }
        //Si ya existe usuario
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
        //Regex de mail y longitud no menor a 10
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email)<10) 
        {
            $_SESSION['error'] = "Email Incorrecto";
            return false;
        }
        //Fuerza de Contraseña
        return $this->StrenghtPass($password);

    }
    /**
     * Revisa que la contraseña sea fuerte, debe contener por lo menos una letra y un número
     * Retorna True si pasa el chequeo
     * Retorna Falso si no, error en variable de Session
     */
    private function StrenghtPass($pass)
    {
        //Longitud no menor de 8
        if (strlen($pass) < 8) {
            $_SESSION['error'] = "Contraseña debe ser de 8 caracteres mínimo";
            return false;
        }
        //Cotiene un número
        if (!preg_match("#[0-9]+#", $pass)) {
            $_SESSION['error'] = "Contraseña debe contener por lo menos un número";
            return false;
        }
        //Contiene una letra
        if (!preg_match("#[a-zA-Z]+#", $pass)) {
            $_SESSION['error'] = "Contraseña debe contener por lo menos una letra";
            return false;
        }     
        return true;
    }
    /**
     * Codifica texto a metodo usado en para Users
     */
    private function EncodePassword($plain)
    {
        $encoder = $this->get('security.encoder_factory')->getEncoder('AppBundle\Entity\User');
        return $encoder->encodePassword($plain,null);
    }
    //------------------------------------Querys-----------------------------------------------------
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