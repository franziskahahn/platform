<?php

namespace Oro\Bundle\NavigationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Oro\Bundle\NavigationBundle\Entity\MenuUpdate;

/**
 * @Route("/menu/organization")
 */
class OrganizationMenuController extends AbstractMenuController
{
    /**
     * {@inheritdoc}
     */
    protected function getOwnershipType()
    {
        return MenuUpdate::OWNERSHIP_ORGANIZATION;
    }

    /**
     * @Route("/", name="oro_navigation_org_menu_index")
     * @Template("OroNavigationBundle:MenuUpdate:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @Route("/{menuName}", name="oro_navigation_org_menu_view")
     * @Template("OroNavigationBundle:MenuUpdate:view.html.twig")
     *
     * @param string $menuName
     *
     * @return array
     */
    public function viewAction($menuName)
    {
        return parent::viewAction($menuName);
    }

    /**
     * @Route("/{menuName}/create/{parentKey}", name="oro_navigation_org_menu_create")
     * @Template("OroNavigationBundle:MenuUpdate:update.html.twig")
     *
     * @param string $menuName
     * @param string|null $parentKey
     *
     * @return array|RedirectResponse
     */
    public function createAction($menuName, $parentKey = null)
    {
        return parent::createAction($menuName, $parentKey);
    }

    /**
     * @Route("/{menuName}/update/{key}", name="oro_navigation_org_menu_update")
     * @Template("OroNavigationBundle:MenuUpdate:update.html.twig")
     *
     * @param string $menuName
     * @param string $key
     *
     * @return array|RedirectResponse
     */
    public function updateAction($menuName, $key)
    {
        return parent::updateAction($menuName, $key);
    }
}
