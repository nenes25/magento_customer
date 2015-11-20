<?php
/**
 * Installation du module Hhennes Customer
 * 
 * Cr�ation d'un nouvel attribut qui demande � l'utilisateur comment il a connu le site
 * Ce param�tre doit �tre disponible dans tous les formulaires de cr�ation de compte
 */


$installer = $this;
$installer->startSetup();

$entityTypeId = $installer->getEntityTypeId('customer');

//Cr�ation d'un nouvel attribut qui demande � l'utilisateur comment il a connu le site
$this->addAttribute('customer', 'website_know', array(
    'input' => 'select',
    'type' => 'int',
    'label' => 'Website Know',
    'source' => 'eav/entity_attribute_source_table',
    'visible' => 1,
    'required' => false,
    'user_defined' => 1,
    'system' => false,
    //'option' => array('values' => array('By a friend', 'By the work', 'By google', 'Other')) 
	//Si on veut ajouter sur un seul store , on peut utiliser la syntaxe comment�e
));

/**
 * Ajout des Options
 */
$websiteOptions = array(
    'attribute_id' => $this->getAttributeId('customer', 'website_know'), //Identifiant de l'attribut
    'value' => array(
        'friend' => array( // Ce tableau n'a pas de cl� num�rique l'option va donc �tre cr��e
            0 => 'By a friend', //Valeur Admin
            1 => 'Par un ami' // Valeur Store 1
        ),
        'work' => array(
            0 => 'By the work',
            1 => 'Par le travail'
        ),
        'google' => array(
            0 => 'By google',
            1 => 'Par google'
        ),
        'other' => array(
            0 => 'Other',
            1 => 'Autre'
        ),
    ),
    'order' => array( //Classement des �l�ments
        'friend' => 1,
        'work' => 2,
        'google' => 3,
        'other' => 4,
    )
);

$this->addAttributeOption($websiteOptions);

/**
 * Ajout de l'attribut aux formulaires n�cessaires
 */
Mage::getModel('eav/config')
        ->getAttribute('customer', 'website_know')
        ->setData('used_in_forms', array(
            'adminhtml_customer',
            'checkout_register',
            'customer_account_create')
        )
        ->save();

//Ajout de l'attribut dans la table salesquote pour les cr�ation de comptes dans le checkout
$tablequote = $this->getTable('sales/quote');
$installer->run("ALTER TABLE $tablequote ADD `website_know` INT(11) NOT NULL");

$installer->endSetup();