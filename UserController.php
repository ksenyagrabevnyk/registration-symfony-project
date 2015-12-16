<?php

namespace AppBundle\Controller;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;


class UserController extends Controller
{

    public function indexAction($name)
    {
        $user = $this->get("security.context")->getToken()->getUser();

        $display_name = " ";
        $is_auth = false;
        if (is_object($user)) {
            $display_name = $user->getDisplayName();
            $is_auth = true;
        }

        return $this->render('AppBundle:User:index.html.twig',
            array(
                'name' => $name,
                'display_name' => $display_name,
                'is_auth' => $is_auth
            ));
    }
    /**
     * Форма реєстрації
     */

    public function addAction()
    {
        $error = '';
        $name = $this->getRequest()->request->get('_username');
        $display_name = $this->getRequest()->request->get('_display_name');
        $password = $this->getRequest()->request->get('_password');

        return $this->render('AppBundle:User:add.html.twig',
            array(
                'name' => $name,
                'display_name' => $display_name,
                'error' => $error
            ));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {

        $error = '';
        $name = $this->getRequest()->request->get('_username');
        $display_name = $this->getRequest()->request->get('_display_name');
        $password = $this->getRequest()->request->get('_password');
        // var_dump($password); die;
        $roleId = 2;
        $role = $this->getDoctrine()->getRepository('AppBundle:Role')->find($roleId);
        //var_dump($role_id); die;

        if ($this->_isUniqueUser($name)) {
            $user = new User();
            $user->setDisplayName($display_name);
            $user->setName($name);
            $user->setPassword($password);
            $user->setRole($role);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();

            return $this->render('AppBundle:Security:login.html.twig',
                array(
                    'name' => $name,
                    'error' => ''
                ));
        } else {
            $error = "Користувач з іменем " . $name . ' уже існує. Будь оригінальний';
        }

        return $this->render("AppBundle:Film:index.html.twig",
            array(
                'name' => $name,

                'error' => $error
            ));

    }

	/**
     * @param $name
     */
    public function errorAction($name)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(array('name' => $name));


    }

    /**
     *Превірка на існування користувача з таким іменем
     * @param string $name
     * @return bool
     */
    private function _isUniqueUser($name)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(array('name' => $name));

//          var_dump($user);
//          exit;
        if(!$user) {
            return true;
        }

        return false;
    }



}

/**
 * Creates a new User entity.
 *
 * @Route("/", name="user_create")
 * @Method("POST")
 * @Template("AppBundle:User:new.html.twig")
 */
/**     public function createAction(Request $request)
{
$entity = new User();
$form = $this->createCreateForm($entity);
$form->handleRequest($request);

if ($form->isValid()) {
$em = $this->getDoctrine()->getManager();
$em->persist($entity);
$em->flush();

return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getIduser())));
}

return array(
'entity' => $entity,
'form'   => $form->createView(),
);
}
 */
/**
 * Creates a form to create a User entity.
 *
 * @param User $entity The entity
 *
 * @return \Symfony\Component\Form\Form The form
 */
/**  private function createCreateForm(User $entity)
{
$form = $this->createForm(new UserType(), $entity, array(
'action' => $this->generateUrl('user_create'),
'method' => 'POST',
));

$form->add('submit', 'submit', array('label' => 'Create'));

return $form;
}
 */
/**
 * Displays a form to create a new User entity.
 *
 * @Route("/new", name="user_new")
 * @Method("GET")
 * @Template()
 */
/**  public function newAction()
{
$entity = new User();
$form   = $this->createCreateForm($entity);

return array(
'entity' => $entity,
'form'   => $form->createView(),
);
}

/**
 * Finds and displays a User entity.
 *
 * @Route("/{id}", name="user_show")
 * @Method("GET")
 * @Template()
 */
/* public function showAction($id)
 {
	 $em = $this->getDoctrine()->getManager();

	 $entity = $em->getRepository('AppBundle:User')->find($id);

	 if (!$entity) {
		 throw $this->createNotFoundException('Unable to find User entity.');
	 }

	 $deleteForm = $this->createDeleteForm($id);

	 return array(
		 'entity'      => $entity,
		 'delete_form' => $deleteForm->createView(),
	 );
 }

 /**
  * Displays a form to edit an existing User entity.
  *
  * @Route("/{id}/edit", name="user_edit")
  * @Method("GET")
  * @Template()
  */
/*  public function editAction($id)
  {
	  $em = $this->getDoctrine()->getManager();

	  $entity = $em->getRepository('AppBundle:User')->find($id);

	  if (!$entity) {
		  throw $this->createNotFoundException('Unable to find User entity.');
	  }

	  $editForm = $this->createEditForm($entity);
	  $deleteForm = $this->createDeleteForm($id);

	  return array(
		  'entity'      => $entity,
		  'edit_form'   => $editForm->createView(),
		  'delete_form' => $deleteForm->createView(),
	  );
  }

  public function frontAction($name)
  {
  }

  /**
   * Creates a form to edit a User entity.
   *
   * @param User $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
/* private function createEditForm(User $entity)
 {
	 $form = $this->createForm(new UserType(), $entity, array(
		 'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
		 'method' => 'PUT',
	 ));

	 $form->add('submit', 'submit', array('label' => 'Update'));

	 return $form;
 }
 /**
  * Edits an existing User entity.
  *
  * @Route("/{id}", name="user_update")
  * @Method("PUT")
  * @Template("AppBundle:User:edit.html.twig")
  */
/* public function updateAction(Request $request, $id)
 {
	 $em = $this->getDoctrine()->getManager();

	 $entity = $em->getRepository('AppBundle:User')->find($id);

	 if (!$entity) {
		 throw $this->createNotFoundException('Unable to find User entity.');
	 }

	 $deleteForm = $this->createDeleteForm($id);
	 $editForm = $this->createEditForm($entity);
	 $editForm->handleRequest($request);

	 if ($editForm->isValid()) {
		 $em->flush();

		 return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
	 }

	 return array(
		 'entity'      => $entity,
		 'edit_form'   => $editForm->createView(),
		 'delete_form' => $deleteForm->createView(),
	 );
 }
 /**
  * Deletes a User entity.
  *
  * @Route("/{id}", name="user_delete")
  * @Method("DELETE")
  */
/*  public function deleteAction(Request $request, $id)
  {
	  $form = $this->createDeleteForm($id);
	  $form->handleRequest($request);

	  if ($form->isValid()) {
		  $em = $this->getDoctrine()->getManager();
		  $entity = $em->getRepository('AppBundle:User')->find($id);

		  if (!$entity) {
			  throw $this->createNotFoundException('Unable to find User entity.');
		  }

		  $em->remove($entity);
		  $em->flush();
	  }

	  return $this->redirect($this->generateUrl('user'));
  }

  /**
   * Creates a form to delete a User entity by id.
   *
   * @param mixed $id The entity id
   *
   * @return \Symfony\Component\Form\Form The form
   */
/*  private function createDeleteForm($id)
  {
	  return $this->createFormBuilder()
		  ->setAction($this->generateUrl('user_delete', array('id' => $id)))
		  ->setMethod('DELETE')
		  ->add('submit', 'submit', array('label' => 'Delete'))
		  ->getForm()
		  ;
  }

  public function addAction()
  {
  }}*/
