SulitDito
=========
SulitDito (http://sulitdito.appnimbus.com) provides anyone with an easy way to sell anything online!

It utilizes the AppNimbus API (http://appnimbus.com) to create a any application quickly and easily.

# Basic Setup
## VirtualHost Config
### In httpd.conf
    <VirtualHost *:80>
        DocumentRoot "<DIRECTORY TO SULITDITO>"
        ServerName sulitdito.local
        <Directory <DIRECTORY TO SULITDITO>>
        </Directory>
    </VirtualHost>
## In C:\Windows\System32\drivers\etc\hosts file (or /etc/hosts in most UNIX-based OS')
    127.0.0.1       sulitdito.local
    
# SQL DB
NONE! This uses the AppNimbus API (http://appnimbus.com)