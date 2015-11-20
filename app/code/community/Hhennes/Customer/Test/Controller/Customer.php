<?php

/**
 *
 */
class Hhennes_Customer_Test_Controller_Customer extends EcomDev_PHPUnit_Test_Case_Controller
{

    /**
     * Test de l'affichage des données sur la page de création de compte
     */
    public function testDisplayInCustomerRegistrationForm(){


        //On dispatche la page d'affichage
        $this->dispatch('customer/account/create/');

        //On s'assure que le layout est chargé
        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();

        //On s'assure que le block est dans le layout et au bon endroit
        $this->assertLayoutBlockCreated('hhennes.customer.fields');
        $this->assertLayoutBlockRendered('hhennes.customer.fields');
        $this->assertLayoutBlockParentEquals('hhennes.customer.fields','form.additional.info');

        //On s'assure que les types du block sont ok
        $this->assertLayoutBlockTypeOf('hhennes.customer.fields','core/template');
        $this->assertLayoutBlockInstanceOf('hhennes.customer.fields','Mage_Core_Block_Template');
        
    }

    public function testDisplayInOnePageCheckoutRegistrationForm()
    {

        //Cette Partie du code provient de https://gist.github.com/schmengler/1632726
        $this->getRequest()->setMethod('POST')
            ->setPost(
                array(
                    'product' => '1',
                    'related_product' => '',
                    'qty' => '1'
                )
        );
        // Set an initial cookie so that the Magento cookie validation passes
        $this->getRequest()->setCookie('mage-test', true);
        $this->dispatch('checkout/cart/add');
        $this->assertResponseHttpCode('302', '/checkout/cart/add/ has not redirected to the cart');
        $this->assertRedirectToUrlRegExp("/^.*checkout.*$/", 'We are not directed to the checkout as expected');
        $this->reset();

        //On dispatche la page d'affichage
        $this->dispatch('checkout/onepage/index');

        //On s'assure que le layout est chargé
        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();

        //On s'assure que le block est dans le layout et au bon endroit
        $this->assertLayoutBlockCreated('hhennes.customer.fields');
        $this->assertLayoutBlockRendered('hhennes.customer.fields');
        $this->assertLayoutBlockParentEquals('hhennes.customer.fields', 'form.additional.info');

        //On s'assure que les types du block sont ok
        $this->assertLayoutBlockTypeOf('hhennes.customer.fields', 'core/template');
        $this->assertLayoutBlockInstanceOf('hhennes.customer.fields', 'Mage_Core_Block_Template');
    }
}
?>
