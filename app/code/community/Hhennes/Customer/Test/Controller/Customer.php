<?php

/**
 * Attention les controllers ne fonctionnent pas avec PHPUnit 5 et supérieur
 * Il faut s'assurer d'avoir une version inférieure pour que ça marche !
 */
class Hhennes_Customer_Test_Controller_Customer extends EcomDev_PHPUnit_Test_Case_Controller {

    /**
     * Test de l'affichage des données sur la page de création de compte
     */
    public function testDisplayInCustomerRegistrationForm() {

        //On dispatche la page d'affichage
        $this->dispatch('customer/account/create/');

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
    
    /**
     * Création de clients depuis le formulaire de création de compte
     * 
     * Les derniers cas échouent en raison de problèmes de session
     * 
     * @dataProvider dataProvider
     */
    public function testCreateCustomerInRegistrationForm($customer) {

        $optionId= null;
        
        //Récupération des codes admin des options
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('customer', 'website_know');
        $options = Mage::getModel('eav/entity_attribute_option')->getCollection()
                ->setAttributeFilter($attributeId)
                ->setStoreFilter(0);
        
        //On récupère l'identifiant de l'option associé
        foreach ($options as $option) {
            if ($option->getDefaultValue() == $customer['website_know']) {
                $optionId = $option->getOptionId();
                break;
            }
        }
        
        //On vérifie qu'une valeur d'option est bien trouvée
        $this->assertNotNull($optionId);

        $this->getRequest()->setMethod('POST')
                ->setPost(
                        array(
                            'firstname' => $customer['firstname'],
                            'lastname' => $customer['lastname'],
                            'email' => sprintf($customer['email'], time()),
                            'password' => $customer['password'],
                            'confirmation' => $customer['confirmation'],
                            'website_know' => $optionId
                        )
        );

        $this->dispatch('customer/account/createPost');
        $customerSession = Mage::getSingleton('customer/session');
        $this->assertNotNull($customerSession->getCustomer());
        $websiteOption = $customerSession->getCustomer()->getData('website_know');
        $this->assertEquals($websiteOption, $optionId);
        
        //
    }

    /**
     * Test de l'affichage des données sur le formulaire de création de compte checkout
     * Pour ce test il faut qu'il y ait au moins un produit sur le site
     * On le rajoute dans les fixtures
     * 
     * @loadFixture
     */
    public function testDisplayInOnePageCheckoutRegistrationForm() {
        
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

    protected function tearDown() {
        $session = Mage::getSingleton('customer/session');
        $session->logout();
        parent::tearDown();
    }

}