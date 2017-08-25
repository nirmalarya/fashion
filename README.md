# README #

### What is this repository for? ###
Code repository of eCommerce Shop with a user experience driven platform to sell Indian Women Wear specially meant for Occasions.

### How do I get set up? ###

* Setup Prerequite
Composer should be installed

* Create Magento 2 Project

composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition scandicdesi
When prompted, enter your [authentication keys](http://devdocs.magento.com/guides/v2.0/install-gde/prereq/connect-auth.html). Your public key is your username; your private key is your password.

* Install Magento 2 

Install Magento 2 using command line or web based wizard.

* Install Magento 2 sample data 

php bin/magento sampledata:deploy

* Setup git and pull code from repo.

Remove composer.json and composer.lock file from your project

git init
git remote add origin https://nirmalarya99@bitbucket.org/scandicdesi/scandicd.git
git pull origin develop

* Update Dependencies using composer

composer update
