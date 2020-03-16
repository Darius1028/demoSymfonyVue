<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class UserController extends ApiController
{
    /**
    * @Route("/user", methods="GET")
    */
    public function index(UserRepository $userRepository)
    {
        $user = $userRepository->transformAll();

        return $this->respond($user);
    }

    /**
    * @Route("/user", methods="POST")
    */
    public function create(Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {
        
        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the name
        if (! $request->get('name')) {
            return $this->respondValidationError('Please provide a name!');
        }
        // validate the lastName
        if (! $request->get('lastName')) {
            return $this->respondValidationError('Please provide a lastName!');
        }
        // validate the email
        if (! $request->get('email')) {
            return $this->respondValidationError('Please provide a email!');
        }
        // validate the titcile
        if (! $request->get('ci')) {
            return $this->respondValidationError('Please provide a ci!');
        }
        // persist the new User
        $user = new User;
        $user->setName($request->get('name'));
        $user->setLastName($request->get('lastName'));
        $user->setEmail($request->get('email'));
        $user->setCi($request->get('ci'));
        $em->persist($user);
        $em->flush();
  
        return $this->respondCreated($userRepository->transform($user));
    }



    /**
     * @Route("/user/{id}", name="user_update", methods="PUT")
     */
    public function update( $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {



        $user = $userRepository->findById($id);

        if (empty($user)) {
            return $this->respondNotFound();
        }

        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

 
        if ($request->get('name')) {
            $user->setName($request->get('name'));
        }

        if ($request->get('lastName')) {
            $user->setLastName($request->get('lastName'));
        }

        if ($request->get('email')) {
            $user->setEmail($request->get('email'));
        }

        if ($request->get('ci')) {
            $user->setCi($request->get('ci'));
        }

        
        $em->flush();

        return $this->respond(['status' => 'User update']);
    }



    /**
     * @Route("/user/{id}", methods="POST")
     */
    public function get( $id, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findById($id);

        if (empty($user)) {
            return $this->respondNotFound();
        }

        return $this->respond($userRepository->transform($user));
    }


    /**
     * @Route("/user/{id}", name="user_delete", methods="DELETE")
     */
    public function delete( $id, UserRepository $userRepository, EntityManagerInterface $em)
    {
        
        return $this->respond($userRepository->removeUser($id));
               
    }





}
