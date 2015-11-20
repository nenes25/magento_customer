<?php

/**
 *
 * Fichier de base pour tester la bonne configuration d'un module
 *
 */
class Hhennes_Customer_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * Paramètres de la classe pour tester automatiquement que le fichier de configuration respecte certaines normes
     * Permets de génériser la création de ce fichier de test pour l'ensemble des modules
     */
    protected $_codePool       = 'community';
    protected $_currentVersion = '0.1.0';
    protected $_useResource    = true;
    protected $_useLayout      = true;
    protected $_nodeName       = 'hhennes_customer'; //Nom utilisé pour les noeud ( models / helpers/ blocks )

    /**
     * Test que le module est actif
     */

    public function testModuleIsActive()
    {
        $this->assertModuleIsActive();
    }

    /**
     * Tests globals sur le module
     */
    public function testModuleGlobal()
    {
        //CodePool
        $this->assertModuleCodePool($this->_codePool);

        //Version du module
        $this->assertModuleVersion($this->_currentVersion);

        //Dépendance du module
        $this->assertModuleDepends('Mage_Customer');
    }

    /**
     * Vérification des conditions de setup du module
     */
    public function testSetupResources()
    {
        if ($this->_useResource) {
            $this->assertSetupResourceDefined();
            $this->assertSetupResourceExists();
        }
    }

    /**
     * Vérification des alias de la classe
     * ( Models/ ResourceModel / Helpers / Blocks )
     */
    public function testClassesAlias()
    {
        //Helper
        $this->assertHelperAlias($this->_nodeName, 'Hhennes_Customer_Helper_Data');
    }

    /**
     * Tests que le layout fonctionne bien
     */
    public function testLayout()
    {
        if ($this->_useLayout) {
            $this->assertLayoutFileDefined('frontend', 'hhennes_customer.xml');
            $this->assertLayoutFileExists('frontend', 'hhennes_customer.xml');
        }
    }

}
?>
