# whmcs-server-allocator
Server allocation hook for whmcs

Tested on WHMSC V8.10 With php81
Tested with cPanel - Should also work for Plesk, Directadmin, and reseller hosting plans


Having multiple Servers in multiple locations often mean setting up multiple product groups in whmcs which all have multiple plans to be set up it becomes a lot of multiples 

with this hook that is no longer an issue and best of all its a solution to what is otherwise normally a very costly solution 

The hooks functions

Provision web hosting accounts to servers based on a single configurable option 
Eliminates the need to create multiple Product Groups for the same service in WHMCS 
Have one Product group for Shared Hosting, cPanel Hosting, Reseller Hosting no matter the location


To use the hook you must first make sure that these steps have been take or it will result in error

First of all the packages in WHM all need to have the same identical names so for example 

Your UK cPanel Server 
Package names created 
Bronze Silver Gold

Your US cPanel Server
Package names created 
Bronze Silver Gold

Your EU cPanel Server
Package names created 
Bronze Silver Gold

Now You have made sure that all your package names match throughout you will need to create a configurable option in whmcs 

To do this click on the spanner icon top right find system settings click on it now on the left hand side look for products and services select that option
Now look for Configuarable options and select it.

The configuarable options defined here will determine which server location the account should be provisioned too

In front of you, you should now see the option Create a new group click on that 
Enter Group Name - Something like cPanel server location
Description - cPanel shared hosting server locations

Assigned Products - Select the plans this configurable option will be assigned to 
Remember you only need to select the plans that are on what you would consider as your default location

Click on save changes

You'll now see the option add new configurable option click on it
Option name - enter - Location
Add Option Enter the location you want to be your default server so if your based in the UK put UK as the first option if your in USA put USA first click save changes then continue to add options untill you have added all the locations such as UK USA GERMANY etc



