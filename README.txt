README
======

This webservice allows you to retrieve urls created by the DCA exporter.
It has a partialdownload service, that you can use to get a section of the full data (for example, only birds).
It has a completedownload service, that allows you to retrieve the full data of multiple versions.
It has a retrievelist service, that allows you to retrieve all the versions that are availble for download.


Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

<VirtualHost *:80>
   DocumentRoot "C:/Users/ayco/Zend/workspaces/DefaultWorkspace7/ETI_BLANK_ZF_PROJECT/public"
   ServerName .local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development
    
   <Directory "C:/Users/ayco/Zend/workspaces/DefaultWorkspace7/ETI_BLANK_ZF_PROJECT/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>
    
</VirtualHost>
