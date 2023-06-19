PAGES edit
/admin/page/create - creating client page:

alias - single lowcase word only letters

single_page = 'yes' - if you only need to get data from table Pages from DB and load data into default view (page_template.blade.php).
single_page = 'no' - if you need to have controller and model and migration and view for page (or alias or title for menu path from db tables)

service_page = 'yes' - if you need to create page with services CRUD (resource controler)
As models for service page - model Pages, model Categories, model Services
As view - resources/other/service_page.blade.php

After creating service page you can add categories and services to page.
Also admin panel has items for creating and editing categories and services.

After creating the page - open files in your editor or IDE and edit.
Also put route in routes/web.php.

SERVICE PAGE edit
In form service_page = yes.
An entry will be created in the database.

SERVICE edit
First create the services, then the masters.

MASTERS edit
All masters must have a some service,
but if you don't need this - the ALL masters should NOT have any services.
Otherwise, in the logic of order processing errors may occur.
Logic:
if each master have at least one service - check appointments times for current master;
if masters not services - we believe that every master performs any service
and check appointments times for all masters
and if at least one master is free - client can sign up.
